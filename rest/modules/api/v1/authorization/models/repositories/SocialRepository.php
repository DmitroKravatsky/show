<?php

namespace rest\modules\api\v1\authorization\models\repositories;

use common\models\userProfile\UserProfileEntity;
use GuzzleHttp\Client;
use rest\modules\api\v1\authorization\models\RestUserEntity;
use yii\web\ErrorHandler;
use yii\web\NotFoundHttpException;
use yii\web\ServerErrorHttpException;
use yii\web\UnprocessableEntityHttpException;
use yii\web\BadRequestHttpException;

/**
 * Class SocialRepository
 * @package rest\modules\api\v1\authorization\models\repositories
 */
trait SocialRepository
{
    /**
     * Vk register
     *
     * @param $params array of post data
     *
     * @return RestUserEntity whether the attributes are valid and the record is inserted successfully
     *
     * @throws BadRequestHttpException
     * @throws ServerErrorHttpException
     * @throws UnprocessableEntityHttpException
     * @throws \yii\db\Exception
     */
    public function vkRegister(array $params): RestUserEntity
    {
        if (!isset($params['email']) && !isset($params['phone_number'])) {
            throw new BadRequestHttpException('Необходимо заполнить «Email» или «Номер телефона».');
        }

        $transaction = \Yii::$app->db->beginTransaction();
        try {
            $client = new Client(['headers' => ['Content-Type' => 'application/json']]);
            $result = $client->request(
                'GET',
                'https://api.vk.com/method/users.get',
                [
                    'query' => [
                        'access_token' => $params['token'],
                        'fields' => 'photo_max',
                    ]
                ]
            );

            if ($result->getStatusCode() == 200) {
                $userData = json_decode($result->getBody()->getContents());
                if (isset($userData->error)) {
                    throw new ServerErrorHttpException;
                }

                $userData = array_shift($userData->response);

                $data = [
                    'source'           => self::VK,
                    'source_id'        => (string) $userData->uid,
                    'terms_condition'  => $params['terms_condition'],
                    'password'         => $pass = \Yii::$app->security->generateRandomString(32),
                    'confirm_password' => $pass
                ];

                if (isset($params['email'])) {
                    $data['email'] = $params['email'];
                } else {
                    $data['phone_number'] = $params['phone_number'];
                }

                $user = new RestUserEntity();
                $user->scenario = self::SCENARIO_REGISTER;
                $user->setAttributes($data);
                if (!$user->save()) {
                    $this->throwModelException($user->errors);
                }

                $userProfile = new UserProfileEntity();
                $userProfile->scenario = UserProfileEntity::SCENARIO_CREATE;
                $userProfile->setAttributes([
                    'name'      => $userData->first_name,
                    'last_name' => $userData->last_name,
                    'user_id'   => $user->id,
                    'avatar'    => $userData->photo_max
                ]);

                if (!$userProfile->save()) {
                    $this->throwModelException($userProfile->errors);
                }

                $transaction->commit();
                return $user;
            }

            throw new ServerErrorHttpException;
        } catch (UnprocessableEntityHttpException $e) {
            $transaction->rollBack();
            throw new UnprocessableEntityHttpException($e->getMessage());
        } catch (\Exception $e) {
            \Yii::error($e->getMessage());
            $transaction->rollBack();
            throw new ServerErrorHttpException('Произошла ошибка при регистрации.');
        }
    }

    /**
     * Vk authorization
     *
     * @param $token string
     *
     * @return RestUserEntity
     *
     * @throws NotFoundHttpException
     * @throws ServerErrorHttpException
     */
    public function vkLogin(string $token): RestUserEntity
    {
        try {
            $client = new Client(['headers' => ['Content-Type' => 'application/json']]);
            $result = $client->request(
                'GET',
                'https://api.vk.com/method/users.get',
                [
                    'query' => [
                        'access_token' => $token,
                        'v'            => 5.71
                    ]
                ]
            );

            if ($result->getStatusCode() == 200) {
                $userData = json_decode($result->getBody()->getContents());
                if (isset($userData->error)) {
                    throw new ServerErrorHttpException;
                }
                return $this->findModelByParams(['source' => self::VK, 'source_id' => array_shift($userData->response)->id]);
            }
            throw new ServerErrorHttpException;
        } catch (NotFoundHttpException $e) {
            throw new NotFoundHttpException('Пользователь не найден, пройдите процедуру регистрации.');
        } catch (\Exception $e) {
            throw new ServerErrorHttpException('Произошла ошибка при авторизации.');
        }
    }

    /**
     * Authorization with Gmail
     *
     * @param $params array of post datagit
     *
     * @param $params array of POST data
     * @return RestUserEntity
     * @throws ServerErrorHttpException
     * @throws UnprocessableEntityHttpException
     */
    public function gmailAuthorization(array $params): RestUserEntity
    {
        $transaction = \Yii::$app->db->beginTransaction();
        try {
            $client = new Client(['headers' => ['Content-Type' => 'application/json']]);
            $result = $client->request(
                'GET',
                'https://www.googleapis.com/oauth2/v1/userinfo',
                [
                    'query' => [
                        'access_token' => $params['access_token'],
                    ]
                ]
            );

            if ($result->getStatusCode() === 200) {
                $userData = json_decode($result->getBody()->getContents());
                if (isset($userData->error)) {
                    throw new ServerErrorHttpException;
                }
                if (isset($userData->id)) {
                    $existedUser = RestUserEntity::find()
                        ->where(['source_id' => $userData->id])
                        ->orWhere(['email' => $userData->email])
                        ->one();
                    if ($existedUser) {
                        return $this->gmailLogin($existedUser);
                    }
                    $newUser = $this->gmailRegister($userData, $params['terms_condition']);

                    $transaction->commit();
                    return $newUser;
                }

                throw new ServerErrorHttpException('Internal server error');
            } else {
                throw new BadRequestHttpException('Bad Request');
            }
        } catch (UnprocessableEntityHttpException $e) {
            $transaction->rollBack();
            throw new UnprocessableEntityHttpException($e->getMessage());
        } catch (\Exception $e) {
            \Yii::error($e->getMessage());
            $transaction->rollBack();
            throw new ServerErrorHttpException($e->getMessage());
        }

    }

    /**
     * Gmail registration
     * @param $termsCondition integer Marked if user accepts condition
     *
     * @return RestUserEntity whether the attributes are valid and the record is inserted successfully
     *
     * @throws ServerErrorHttpException
     * @throws UnprocessableEntityHttpException
     * @throws \yii\db\Exception
     */
    public function gmailRegister($userData, $termsCondition): RestUserEntity
    {
        $transaction = \Yii::$app->db->beginTransaction();

        try {
            $data = [
                'source'           => self::GMAIL,
                'source_id'        => $userData->id,
                'terms_condition'  => $termsCondition,
                'password'         => $pass = \Yii::$app->security->generateRandomString(10),
            ];

            if (isset($userData->email)) {
                $data['email'] = $userData->email;
            }
            if (isset($userData->phone_number)) {
                $data['phone_number'] = $userData->phone_number;
            }
            $user = new RestUserEntity();
            $user->scenario = self::SCENARIO_SOCIAL_REGISTER;
            $user->setAttributes($data);

            if (!$user->save(false)) {
                throw new ServerErrorHttpException($user->errors);
            }
            $viewPath = '@common/views/mail/sendPassword-html.php';
            if (!empty($userData->email)) {
                \Yii::$app->sendMail->run(
                    $viewPath,
                    ['email' => $user->email, 'password' => $pass],
                    \Yii::$app->params['supportEmail'], $user->email, 'Your password'
                );
            }
            $userProfile = new UserProfileEntity();
            $userProfile->scenario = UserProfileEntity::SCENARIO_CREATE;
            $userProfile->setAttributes([
                'name'      => $userData->name,
                'last_name' => $userData->family_name,
                'user_id'   => $user->id,
                'avatar'    => $userData->picture
            ]);

            if (!$userProfile->save(false)) {
                throw new ServerErrorHttpException($user->errors);
            }

            $user->refresh_token = $user->getRefreshToken(['user_id' => $user->id]);
            $user->created_refresh_token = time();
            $user->save(false, ['refresh_token', 'created_refresh_token']);
            $transaction->commit();

            return $user;
        } catch (ServerErrorHttpException $e) {
            $transaction->rollBack();
            \Yii::error($e->getMessage());
            throw new ServerErrorHttpException($e->getMessage());
        }

    }

    /**
     * Gmail login
     *
     * @param RestUserEntity $user
     *
     * @return RestUserEntity
     *
     * @throws ServerErrorHttpException
     */
    public function gmailLogin(RestUserEntity $user): RestUserEntity
    {
        if (RestUserEntity::isRefreshTokenExpired($user->created_refresh_token)) {
            $user->created_refresh_token = time();
            $user->refresh_token = $user->getRefreshToken(['user_id' => $user->id]);

            if (!$user->save(false)) {
                throw new ServerErrorHttpException('Server internal error');
            }
        }
        return $user;

    }

    /**
     * Identify user with FaceBook
     *
     * @param array $params
     * @return RestUserEntity
     * @throws BadRequestHttpException
     * @throws ServerErrorHttpException
     * @throws UnprocessableEntityHttpException
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \yii\db\Exception
     */
    public function fbAuthorization(array $params): RestUserEntity
    {
        $transaction = \Yii::$app->db->beginTransaction();
        try {
            $client = new Client(['headers' => ['Content-Type' => 'application/json']]);
            $result = $client->request(
                'GET',
                'https://graph.facebook.com/me',
                [
                    'query' => [
                        'access_token' => $params['access_token'],
                        'fields'       => 'id, first_name, last_name, picture.type(large), email',
                        'v'            => '2.12'
                    ]
                ]
            );

            if ($result->getStatusCode() == 200) {
                $userData = json_decode($result->getBody()->getContents());

                if (isset($userData->id)) {
                    $existedUser = RestUserEntity::find()
                        ->where(['source_id' => $userData->id])
                        ->orWhere(['email' => $userData->email])
                        ->one();
                    if ($existedUser) {
                        return $this->fbLogin($existedUser);
                    }

                    $newUser = $this->fbRegister($userData, $params['terms_condition']);

                    $transaction->commit();
                    return $newUser;
                }
                throw new ServerErrorHttpException('Internal server error');

            } else {
                throw new BadRequestHttpException('Bad Request');
            }
        } catch (UnprocessableEntityHttpException $e) {
            $transaction->rollBack();
            throw new UnprocessableEntityHttpException($e->getMessage());
        } catch (BadRequestHttpException $e) {
            $transaction->rollBack();
            throw new BadRequestHttpException($e->getMessage());
        } catch (\Exception $e) {
            \Yii::error($e->getMessage());
            $transaction->rollBack();
            throw new ServerErrorHttpException($e->getMessage());
        }
    }

    /**
     * Facebook login
     * @param RestUserEntity $user
     * @return mixed
     * @throws ServerErrorHttpException
     */
    public function fbLogin(RestUserEntity $user): RestUserEntity
    {
        if (RestUserEntity::isRefreshTokenExpired($user->created_refresh_token)) {
            $user->created_refresh_token = time();
            $user->refresh_token = $user->getRefreshToken(['user_id' => $user->id]);

            if (!$user->save(false)) {
                throw new ServerErrorHttpException('Server internal error');
            }
        }
        return $user;
    }

    /**
     * Add user to a system
     * @param $userData
     * @param $termsCondition integer Marked if user accepts condition
     * @return RestUserEntity
     * @throws UnprocessableEntityHttpException
     * @throws ServerErrorHttpException
     */
    public function fbRegister($userData, $termsCondition): RestUserEntity
    {
        $transaction = \Yii::$app->db->beginTransaction();
        try {
            $data = [
                'source'           => self::FB,
                'source_id'        => $userData->id,
                'terms_condition'  => $termsCondition,
                'password'         => $pass = \Yii::$app->security->generateRandomString(10),
            ];

            if (isset($userData->email)) {
                $data['email'] = $userData->email;
            }
            if (isset($userData->phone_number)) {
                $data['phone_number'] = $userData->phone_number;
            }
            $user = new RestUserEntity();
            $user->scenario = self::SCENARIO_SOCIAL_REGISTER;
            $user->setAttributes($data);

            if (!$user->save(false)) {
                $this->throwModelException($user->errors);
            }

            $viewPath = '@common/views/mail/sendPassword-html.php';
            if (!empty($userData->email)) {
                \Yii::$app->sendMail->run(
                    $viewPath,
                    ['email' => $user->email, 'password' => $pass],
                    \Yii::$app->params['supportEmail'], $user->email, 'Your password'
                );
            }
            $userProfile = new UserProfileEntity();
            $userProfile->scenario = UserProfileEntity::SCENARIO_CREATE;
            $userProfile->setAttributes([
                'name'      => $userData->first_name,
                'last_name' => $userData->last_name,
                'user_id'   => $user->id,
                'avatar'    => $userData->picture->data->url
            ]);

            if (!$userProfile->save()) {
                $this->throwModelException($userProfile->errors);
            }
            $user->refresh_token = $user->getRefreshToken(['user_id' => $user->id]);
            $user->created_refresh_token = time();
            $user->save(false, ['refresh_token', 'created_refresh_token']);
            $transaction->commit();

            return $user;
        } catch (ServerErrorHttpException $e) {
            $transaction->rollBack();
            \Yii::error(ErrorHandler::convertExceptionToString($e));
            throw new ServerErrorHttpException($e->getMessage());
        }


    }

    /**
     * Finds a User by params
     *
     * @param $params array
     *
     * @return null|static
     *
     * @throws NotFoundHttpException
     */
    protected function findModelByParams(array $params)
    {
        if (empty($user = RestUserEntity::findOne((array) $params))) {
            throw new NotFoundHttpException();
        }
        return $user;
    }
}

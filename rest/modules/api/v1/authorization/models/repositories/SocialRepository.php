<?php

namespace rest\modules\api\v1\authorization\models\repositories;

use common\models\userProfile\UserProfileEntity;
use GuzzleHttp\Client;
use rest\behaviors\ResponseBehavior;
use rest\behaviors\ValidationExceptionFirstMessage;
use rest\modules\api\v1\authorization\models\RestUserEntity;
use yii\web\NotFoundHttpException;
use yii\web\ServerErrorHttpException;
use Yii;
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
     * @param $params
     * @return array|bool
     * @throws BadRequestHttpException
     * @throws ServerErrorHttpException
     * @throws UnprocessableEntityHttpException
     * @throws \yii\db\Exception
     */
    public function vkRegister($params)
    {
        if (!isset($params['email']) && !isset($params['phone_number'])) {
            throw new BadRequestHttpException('Необходимо заполнить «Email» или «Номер телефона».');
        }

        $transaction = Yii::$app->db->beginTransaction();
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
                    'password_hash'    => $pass = Yii::$app->security->generateRandomString(32),
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
                    return (new ValidationExceptionFirstMessage())->throwModelException($user->errors);
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
                    return (new ValidationExceptionFirstMessage())->throwModelException($userProfile->errors);
                }

                $transaction->commit();
                return (new ResponseBehavior())->setResponse(
                    201, 'Регистрация прошла успешно.', ['access_token' => $user->getJWT(['user_id' => $user->id])]
                );
            }

            throw new ServerErrorHttpException;
        } catch (UnprocessableEntityHttpException $e) {
            $transaction->rollBack();
            throw new UnprocessableEntityHttpException($e->getMessage());
        } catch (\Exception $e) {
            $transaction->rollBack();
            throw new ServerErrorHttpException('Произошла ошибка при регистрации.');
        }
    }

    /**
     * Vk authorization
     * @param $token
     * @return array
     * @throws NotFoundHttpException
     * @throws ServerErrorHttpException
     */
    public function vkLogin($token)
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

                $uid = array_shift($userData->response)->id;

                if (empty($user = RestUserEntity::findOne(['source' => self::VK, 'source_id' => $uid]))) {
                    throw new NotFoundHttpException;
                }

                return (new ResponseBehavior())->setResponse(
                    200, 'Авторизация прошла успешно.', ['access_token' => $user->getJWT(['user_id' => $user->id])
                ]);
            }
            throw new ServerErrorHttpException;
        } catch (NotFoundHttpException $e) {
            throw new NotFoundHttpException('Пользователь не найден, пройдите процедуру регистрации.');
        } catch (\Exception $e) {
            throw new ServerErrorHttpException('Произошла ошибка при авторизации.');
        }
    }

    /**
     * Gmail register
     * @param $params
     * @return array|bool
     * @throws ServerErrorHttpException
     * @throws UnprocessableEntityHttpException
     * @throws \yii\db\Exception
     */
    public function gmailRegister($params)
    {
        $transaction = Yii::$app->db->beginTransaction();

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

            if ($result->getStatusCode() == 200) {
                $userData = json_decode($result->getBody()->getContents());
                $data = [
                    'source'           => self::GMAIL,
                    'source_id'        => (string) $userData->id,
                    'terms_condition'  => $params['terms_condition'],
                    'email'            => $userData->email,
                    'password_hash'    => $pass = Yii::$app->security->generateRandomString(32),
                    'confirm_password' => $pass
                ];

                $user = new RestUserEntity();
                $user->scenario = RestUserEntity::SCENARIO_REGISTER;
                $user->setAttributes($data);

                if (!$user->save()) {
                    return (new ValidationExceptionFirstMessage())->throwModelException($user->errors);
                }

                $userProfile = new UserProfileEntity();
                $userProfile->scenario = UserProfileEntity::SCENARIO_CREATE;
                $userProfile->setAttributes([
                    'name'      => $userData->given_name,
                    'last_name' => $userData->family_name,
                    'user_id'   => $user->id,
                    'avatar'    => $userData->picture
                ]);

                if (!$userProfile->save()) {
                    return (new ValidationExceptionFirstMessage())->throwModelException($userProfile->errors);
                }

                $transaction->commit();
                return (new ResponseBehavior())->setResponse(
                    201, 'Регистрация прошла успешно.', ['access_token' => $user->getJWT(['user_id' => $user->id])]
                );
            }

            $transaction->rollBack();
            throw new ServerErrorHttpException;
        } catch (UnprocessableEntityHttpException $e) {
            $transaction->rollBack();
            throw new UnprocessableEntityHttpException($e->getMessage());
        } catch (\Exception $e) {
            $transaction->rollBack();
            throw new ServerErrorHttpException('Произошла ошибка при регистрации.');
        }
    }

    /**
     * Gmail authorization
     * @param $token
     * @return array
     * @throws NotFoundHttpException
     * @throws ServerErrorHttpException
     */
    public function gmailLogin($token): array
    {
        try {
            $client = new Client(['headers' => ['Content-Type' => 'application/json']]);
            $result = $client->request(
                'GET',
                'https://www.googleapis.com/oauth2/v1/userinfo',
                [
                    'query' => [
                        'access_token' => $token,
                    ]
                ]
            );

            if ($result->getStatusCode() == 200) {
                $userData = json_decode($result->getBody()->getContents());

                if (empty($user = RestUserEntity::findOne(['source' => self::GMAIL, 'source_id' => (string) $userData->id]))) {
                    throw new NotFoundHttpException;
                }

                return (new ResponseBehavior())->setResponse(
                    200, 'Авторизация прошла успешно.', ['access_token' => $user->getJWT(['user_id' => $user->id])]
                );
            }

            throw new ServerErrorHttpException;
        } catch (NotFoundHttpException $e) {
            throw new NotFoundHttpException('Пользователь не найден, пройдите процедуру регистрации.');
        } catch (\Exception $e) {
            throw new ServerErrorHttpException('Произошла ошибка при авторизации.');
        }
    }

    /**
     * Facebook register
     * @param $params
     * @return array|bool
     * @throws ServerErrorHttpException
     * @throws UnprocessableEntityHttpException
     * @throws \yii\db\Exception
     */
    public function fbRegister($params)
    {
        $transaction = Yii::$app->db->beginTransaction();

        try {
            $client = new Client(['headers' => ['Content-Type' => 'application/json']]);
            $requestResult = $client->request(
                'GET',
                'https://graph.facebook.com/me',
                [
                    'query' => [
                        'access_token' => $params['token'],
                        'fields'       => 'id, first_name, last_name, picture.type(large), email',
                        'v'            => '2.12'
                    ]
                ]
            );

            if ($requestResult->getStatusCode() == 200) {
                $userData = json_decode($requestResult->getBody()->getContents());
                $data = [
                    'source'           => self::FB,
                    'source_id'        => (string) $userData->id,
                    'terms_condition'  => $params['terms_condition'],
                    'password_hash'    => $pass = Yii::$app->security->generateRandomString(32),
                    'confirm_password' => $pass
                ];

                if (isset($userData->email)) {
                    $data['email'] = $userData->email;
                } elseif (isset($params['phone_number'])) {
                    $data['phone_number'] = $params['phone_number'];
                }

                $user = new RestUserEntity();
                $user->scenario = self::SCENARIO_REGISTER;
                $user->setAttributes($data);
                if (!$user->save()) {
                    return (new ValidationExceptionFirstMessage())->throwModelException($user->errors);
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
                    return (new ValidationExceptionFirstMessage())->throwModelException($userProfile->errors);
                }

                $transaction->commit();
                return (new ResponseBehavior())->setResponse(
                    201, 'Регистрация прошла успешно.', ['access_token' => $user->getJWT(['user_id' => $user->id])]
                );
            }

            $transaction->rollBack();
            throw new ServerErrorHttpException;
        } catch (UnprocessableEntityHttpException $e) {
            $transaction->rollBack();
            throw new UnprocessableEntityHttpException($e->getMessage());
        } catch (\Exception $e) {
            $transaction->rollBack();
            throw new ServerErrorHttpException('Произошла ошибка при регистрации.');
        }
    }

    /**
     * Facebook login
     * @param $token
     * @return array
     * @throws NotFoundHttpException
     * @throws ServerErrorHttpException
     */
    public function fbLogin($token): array
    {
        try {
            $client = new Client(['headers' => ['Content-Type' => 'application/json']]);
            $result = $client->request(
                'GET',
                'https://graph.facebook.com/me',
                [
                    'query' => [
                        'access_token' => $token,
                        'fields'       => 'id',
                        'v'            => '2.12'
                    ]
                ]
            );

            if ($result->getStatusCode() == 200) {
                $userData = json_decode($result->getBody()->getContents());

                if (empty($user = RestUserEntity::findOne(['source' => self::FB, 'source_id' => (string) $userData->id]))) {
                    throw new NotFoundHttpException;
                }

                return (new ResponseBehavior())->setResponse(
                    200, 'Авторизация прошла успешно.', ['access_token' => $user->getJWT(['user_id' => $user->id])]
                );
            }

            throw new ServerErrorHttpException;
        } catch (NotFoundHttpException $e) {
            throw new NotFoundHttpException('Пользователь не найден, пройдите процедуру регистрации.');
        } catch (\Exception $e) {
            throw new ServerErrorHttpException('Произошла ошибка при авторизации.');
        }
    }
}
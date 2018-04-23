<?php

namespace rest\modules\api\v1\authorization\models\repositories;

use rest\modules\api\v1\authorization\models\BlockToken;
use rest\modules\api\v1\authorization\models\RestUserEntity;
use yii\base\ErrorHandler;
use yii\base\Exception;
use yii\web\{
    NotFoundHttpException, ServerErrorHttpException, UnauthorizedHttpException, UnprocessableEntityHttpException
};

/**
 * Class AuthorizationRepository
 * @package rest\modules\api\v1\authorization\models\repositories
 */
trait AuthorizationRepository
{
    /**
     * Add new user to db with the set of income data
     *
     * @param $params array of POST data
     *
     * @return RestUserEntity whether the attributes are valid and the record is inserted successfully.
     *
     * @throws ServerErrorHttpException
     * @throws UnprocessableEntityHttpException
     */
    public function register(array $params)
    {
        $transaction = \Yii::$app->db->beginTransaction();

        try {
            $user = new RestUserEntity();
            $user->setScenario(self::SCENARIO_REGISTER);
            $user->setAttributes([
                'source'                => self::NATIVE,
                'phone_number'          => $params['phone_number'],
                'terms_condition'       => $params['terms_condition'],
                'password'              => $params['password'],
                'confirm_password'      => $params['confirm_password'],
                'verification_code'     => rand(1000, 9999),
            ]);

            if (!$user->validate()) {
                return $this->throwModelException($user->errors);
            }

            if (!$user->save()) {
                return $this->throwModelException($user->errors);
            }

            \Yii::$app->sendSms->run('Ваш код верификации', $user->phone_number);

            $transaction->commit();

            return $user;
        } catch (UnprocessableEntityHttpException $e) {
            $transaction->rollBack();
            throw new UnprocessableEntityHttpException($e->getMessage());
        } catch (ServerErrorHttpException $e) {
            $transaction->rollBack();
            \Yii::error($e->getMessage());
            throw new ServerErrorHttpException('Произошла ошибка при регистрации.');
        }
    }

    /**
     * Login user in a system
     *
     * @param $params array of the POST data
     *
     * @return null|AuthorizationRepository|RestUserEntity
     *
     * @throws NotFoundHttpException
     * @throws UnprocessableEntityHttpException
     */
    public function login(array $params)
    {
        $user = new self();
        $user->setScenario(self::SCENARIO_LOGIN);
        $user->setAttributes($params);
        if (!$user->validate()) {
            $this->throwModelException($user->errors);
        }

        /** @var RestUserEntity $user */
        $user = $this->getUserByPhoneNumber($params); // todo  для чего ты это вообще делаешь?
        if ($user->validatePassword($params['password'])) {
            return $user;
        }

        return null;
    }

    /**
     * Get user's data from db
     *
     * @param $params array of the POST data
     *
     * @return RestUserEntity
     *
     * @throws NotFoundHttpException if there is no such user
     */
    protected function getUserByParams(array $params)
    {
        if (isset($params['email']) && !empty($user = self::findOne(['email' => $params['email']]))) {
            return $user;
        } elseif (
            isset($params['phone_number'])
            && !empty($user = self::findOne(['phone_number' => $params['phone_number']]))
        ) {
            return $user;
        }

        throw new NotFoundHttpException('Пользователь не найден, пройдите этап регистрации.');
    }

    /**
     * @param string $phoneNumber
     *
     * @return RestUserEntity
     *
     * @throws NotFoundHttpException
     */
    protected function getUserByPhoneNumber(string $phoneNumber): RestUserEntity
    {
        $user = self::findOne(['phoneNumber' => $phoneNumber]);
        if ($user) {
            return $user;
        }
        throw new NotFoundHttpException('No such user');
    }

    /**
     * Notes new users password in db
     *
     * @param $params array of the POST data
     *
     * @return bool the user's record was updated with a new password successfully
     *
     * @throws NotFoundHttpException
     * @throws UnprocessableEntityHttpException
     */
    public function updatePassword(array $params)
    {
        $userModel = RestUserEntity::findOne(\Yii::$app->user->id);
        $userModel->setScenario(RestUserEntity::SCENARIO_UPDATE_PASSWORD);
        $userModel->setAttributes($params);

        if (!$userModel->validate()) {
            return $this->throwModelException($userModel->errors);
        }

        $userModel->password = $params['new_password'];

        if ($userModel->save(false)) {
            return true;
        }
        
        $this->throwModelException($userModel->errors);
    }

    /**
     * @return null|RestUserEntity
     *
     * @throws NotFoundHttpException
     */
    public function loginGuest()
    {
        /** @var RestUserEntity $userModel */
        $userModel = $this->getUserByParams(['email' => \Yii::$app->params['guest-email']]);
        if ($userModel && $userModel->validatePassword(\Yii::$app->params['guest-password'])) {
            return $userModel;
        }
        return null;
    }

    /**
     * Create new access token using refresh_token
     *
     * @return array
     *
     * @throws UnauthorizedHttpException
     * @throws UnprocessableEntityHttpException
     * @throws NotFoundHttpException
     * @throws ServerErrorHttpException
     */
    public function generateNewAccessToken()
    {
        $currentRefreshToken = \Yii::$app->getRequest()->getBodyParam('refresh_token');

        try {
            $user = RestUserEntity::findOne(RestUserEntity::getRefreshTokenId($currentRefreshToken));
            if (!$user) {
                throw new NotFoundHttpException('No such user'); // todo User not found
            }
            if (RestUserEntity::isRefreshTokenExpired($user->created_refresh_token)) {
                throw new UnauthorizedHttpException('Expired token'); // todo Refresh token was expired
            }
            if ($user->refresh_token !== $currentRefreshToken) {
                throw new UnprocessableEntityHttpException('Refresh token is invalid');
            }

            $newAccessToken = $user->getJWT();
            $user->refresh_token = $user->getRefreshToken(['id' => $user->id]);
            $user->created_refresh_token = time();

            if (!$user->save()) {
                return $this->throwModelException($user->errors); // todo метод не подсвечивается
            }

            return [
                'access_token'  => $newAccessToken,
                'refresh_token' => $user->refresh_token,
                'exp'           => RestUserEntity::getPayload($newAccessToken, 'exp'),
                'user'          => [
                    'id'           => $user->getId(),
                    'phone_number' => $user->phone_number,
                    'role'         => $user->getUserRole($user->id),
                    'created_at'   => $user->created_at,
                    'status'       => $user->status
                ]
            ];
        } catch (UnauthorizedHttpException $e) {
            throw new UnauthorizedHttpException($e->getMessage());
        } catch (NotFoundHttpException $e) {
            throw new NotFoundHttpException($e->getMessage());
        } catch (UnprocessableEntityHttpException $e) {
            throw new UnprocessableEntityHttpException($e->getMessage());
        } catch (Exception $e){
            \Yii::error(ErrorHandler::convertExceptionToString($e));
            throw new ServerErrorHttpException('Произошла ошибка при генерации нового токена.');
        }
    }

    /**
     * Changes the status of user's account
     *
     * @param $params array of the POST input data
     *
     * @return RestUserEntity
     *
     * @throws NotFoundHttpException
     * @throws UnprocessableEntityHttpException
     * @throws ServerErrorHttpException
     */
    public function verifyUser(array $params)
    {
        $user = new RestUserEntity();
        $user->setScenario(self::SCENARIO_VERIFY_PROFILE);
        try {
            $user->setAttributes([
                'verification_code' => $params['verification_code'] ?? null,
                'phone_number'      => $params['phone_number'] ?? null,
            ]);
            if (!$user->validate()) {
                return $this->throwModelException($user->errors);
            }

            $user = RestUserEntity::findOne(['phone_number' => $user->phone_number]);
            if (!$user) {
                throw new NotFoundHttpException('User not found');
            }

            if ($user->verification_code !== (int)($params['verification_code'])) {
                throw new UnprocessableEntityHttpException('Wrong verification code');
            }

            $user->status = RestUserEntity::STATUS_VERIFIED;
            $user->verification_code = null;
            $user->refresh_token = $user->getRefreshToken(['id' => $user->id]);// todo зачем?!!!!
            $user->created_refresh_token = time();  // todo зачем?!!!!


            if (!$user->save()) {
                return $this->throwModelException($user->errors);
            }
            return $user; // todo не смущает такое http://joxi.ru/krDp18Wt0pjVpr ?
        } catch (UnprocessableEntityHttpException $e) {
            throw new UnprocessableEntityHttpException($e->getMessage());
        } catch (NotFoundHttpException $e) {
            throw new NotFoundHttpException($e->getMessage());
        } catch (ServerErrorHttpException $e) {
            throw new ServerErrorHttpException('Internal server error');
        }
    }

    /**
     * Logout user from a system
     *
     * @return bool
     * @throws ServerErrorHttpException
     */
    public function logout()
    {
        $restUser = RestUserEntity::findOne(\Yii::$app->user->id);

        try {
            $restUser->addBlackListToken($restUser->getAuthKey());
            return true;
        } catch (ServerErrorHttpException $e) {
            throw new ServerErrorHttpException();
        }
    }

    /**
     * Check the token for the block
     *
     * @param bool
     * @return bool
     */
    public static function isAlreadyBlocked($token)
    {
        if (BlockToken::find()->where(['token' => $token])->one()) {
            return true;
        }
        return false;
    }
}
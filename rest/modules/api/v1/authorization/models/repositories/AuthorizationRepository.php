<?php

namespace rest\modules\api\v1\authorization\models\repositories;

use common\models\userProfile\UserProfileEntity;
use rest\modules\api\v1\authorization\models\RestUserEntity;
use yii\filters\auth\HttpBearerAuth;
use yii\web\NotFoundHttpException;
use yii\web\ServerErrorHttpException;
use Yii;
use yii\web\UnprocessableEntityHttpException;

/**
 * Class AuthorizationRepository
 * @package rest\modules\api\v1\authorization\models\repositories
 */
trait AuthorizationRepository
{
    /**
     * @param $params
     * @return array|bool
     * @throws ServerErrorHttpException
     * @throws UnprocessableEntityHttpException
     * @throws \yii\db\Exception
     */
    public function register($params)
    {
        $transaction = Yii::$app->db->beginTransaction();
        $refresh_token = \Yii::$app->security->generateRandomString(100);

        try {
            $user = new RestUserEntity();
            $user->setScenario(self::SCENARIO_REGISTER);
            $user->setAttributes([
                'source'             => self::NATIVE,
                'phone_number'       => $params['phone_number'] ?? null,
                'email'              => $params['email'] ?? null,
                'terms_condition'    => $params['terms_condition'] ?? 0,
                'password'           => $params['password'] ?? null,
                'confirm_password'   => $params['confirm_password'] ?? null,
                'refresh_token'      => $refresh_token,
                'token_created_date' => time(),
            ]);

            if (!$user->validate()) {
                return $this->throwModelException($user->errors);
            }

            if (!$user->save()) {
                return $this->throwModelException($user->errors);
            }

            $userProfile = new UserProfileEntity();
            $userProfile->setScenario(UserProfileEntity::SCENARIO_CREATE);
            $userProfile->setAttributes([
                'name'      => $params['name'] ?? null,
                'last_name' => $params['last_name'] ?? null,
                'user_id'   => $user->id,
                'avatar'    => $params['avatar'] ?? null
            ]);

            if (!$userProfile->save()) {
                return $this->throwModelException($userProfile->errors);
            }

            $transaction->commit();

            return $user;
        } catch (UnprocessableEntityHttpException $e) {
            $transaction->rollBack();
            throw new UnprocessableEntityHttpException($e->getMessage());
        } catch (ServerErrorHttpException $e) {
            $transaction->rollBack();
            Yii::error($e->getMessage());
            throw new ServerErrorHttpException('Произошла ошибка при регистрации.');
        }
    }

    /**
     * @param $params
     * @return null|AuthorizationRepository|RestUserEntity
     * @throws NotFoundHttpException
     * @throws UnprocessableEntityHttpException
     */
    public function login($params)
    {
        $user = new self();
        $user->setScenario(self::SCENARIO_LOGIN);
        $user->setAttributes($params);
        if (!$user->validate()) {
            $this->throwModelException($user->errors);
        }

        /** @var RestUserEntity $user */
        $user = $this->getUserByParams($params);
        if ($user->validatePassword($params['password'])) {
            return $user;
        }

        return null;
    }

    /**
     * @param $params
     * @return mixed
     * @throws NotFoundHttpException
     */
    protected function getUserByParams($params)
    {
        if (isset($params['email']) && !empty($user = self::findOne(['email' => $params['email']]))) {
            return $user;
        } elseif (isset($params['phone_number']) && !empty($user = self::findOne(['phone_number' => $params['phone_number']]))) {
            return $user;
        }

        throw new NotFoundHttpException('Пользователь не найден, пройдите этап регистрации.');
    }

    /**
     * @param $params
     * @return bool
     * @throws NotFoundHttpException
     * @throws UnprocessableEntityHttpException
     */
    public function updatePassword($params)
    {
        $userModel = RestUserEntity::findOne(Yii::$app->user->id);
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
     * @throws NotFoundHttpException
     */
    public function loginGuest()
    {
        /** @var RestUserEntity $userModel */
        $userModel = $this->getUserByParams(['email' => Yii::$app->params['guest-email']]);
        if ($userModel && $userModel->validatePassword(Yii::$app->params['guest-password'])) {
            return $userModel;
        }
        return null;
    }

    /**
     * @param $roleName
     * @return array
     */
    public function findByRole($roleName):array 
    {
        return Yii::$app->authManager->getUserIdsByRole($roleName);
    }

    public function generateNewAccessToken()
    {
        $oldAccessToken = $this->getAuthKey();
        $userModel = RestUserEntity::findIdentityByAccessToken($oldAccessToken, HttpBearerAuth::className());
        $userId = $userModel->id;
        $currentRefreshToken = \Yii::$app->getRequest()->getBodyParam('refresh_token');

        $user = RestUserEntity::findOne(['refresh_token' => $currentRefreshToken, 'id' => $userId]);

        if (!$user) {
            throw new NotFoundHttpException('Пользователь с таким токеном не найден.');
        }
        $transaction = \Yii::$app->db->beginTransaction();
        try {
            $user->addBlackListToken($oldAccessToken);
            $newAccessToken = $user->getJWT();

            $transaction->commit();

            return [
                'access_token'  => $newAccessToken,
                'refresh_token' => $user->refresh_token,
                'exp'  => RestUserEntity::getPayload($newAccessToken, 'exp'),
                'user' => [
                    'id'         => $user->getId(),
                    'email'      => $user->email,
                    'role'       => $user->getUserRole($user->id),
                    'created_at' => $user->created_at
                ]
            ];

        } catch (ExceptionDb $e) {
            $transaction->rollBack();
            throw new HttpException(422, $e->getMessage());
        } catch (Exception $e){
            $transaction->rollBack();
            Yii::error(ErrorHandler::convertExceptionToString($e));
            throw new ServerErrorHttpException('Произошла ошибка при генерации нового токена.');
        }
    }
}
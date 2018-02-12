<?php

namespace rest\modules\api\v1\authorization\models\repositories;

use common\models\userProfile\UserProfileEntity;
use rest\modules\api\v1\authorization\models\RestUserEntity;
use yii\web\BadRequestHttpException;
use yii\web\ServerErrorHttpException;
use Yii;
use rest\behaviors\ValidationExceptionFirstMessage;
use yii\web\UnauthorizedHttpException;
use yii\web\UnprocessableEntityHttpException;
use rest\behaviors\ResponseBehavior;
use yii\web\NotFoundHttpException;

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

        try {
            $user = new RestUserEntity();
            $user->setScenario(self::SCENARIO_REGISTER);
            $user->setAttributes([
                'source'           => self::NATIVE,
                'phone_number'     => $params['phone_number'] ?? null,
                'email'            => $params['email'] ?? null,
                'terms_condition'  => $params['terms_condition'] ?? 0,
                'password'         => $params['password'] ?? null,
                'confirm_password' => $params['confirm_password'] ?? null,
            ]);

            if (!$user->validate()) {
                return (new ValidationExceptionFirstMessage())->throwModelException($user->errors);
            }

            if (!$user->save()) {
                return (new ValidationExceptionFirstMessage())->throwModelException($user->errors);
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
                return (new ValidationExceptionFirstMessage())->throwModelException($userProfile->errors);
            }

            $transaction->commit();
            return (new ResponseBehavior())->setResponse(
                201, 'Регистрация прошла успешно.', ['access_token' => $user->getJWT(['user_id' => $user->id])]
            );
        } catch (UnprocessableEntityHttpException $e) {
            $transaction->rollBack();
            throw new UnprocessableEntityHttpException($e->getMessage());
        } catch (ServerErrorHttpException $e) {
            $transaction->rollBack();
            throw new ServerErrorHttpException('Произошла ошибка при регистрации.');
        }
    }

    /**
     * @param $params
     * @return array|bool
     * @throws UnauthorizedHttpException
     * @throws UnprocessableEntityHttpException
     */
    public function login($params)
    {
        $user = new self();
        $user->setScenario(self::SCENARIO_LOGIN);
        $user->setAttributes($params);

        if (!$user->validate()) {
            return (new ValidationExceptionFirstMessage())->throwModelException($user->errors);
        }

        $user = $this->getUser($params);

        if (Yii::$app->getSecurity()->validatePassword($params['password'], $user->password)) {
            return (new ResponseBehavior())->setResponse(
                200, 'Авторизация прошла успешно.', ['access_token' => $user->getJWT(['user_id' => $user->id])]
            );
        }

        throw new UnauthorizedHttpException('Ошибка авторизации.');
    }

    /**
     * @param $params
     * @return mixed
     */
    protected function getUser($params)
    {
        if (isset($params['email'])) {
            return self::findOne(['email' => $params['email']]);
        }

        return self::findOne(['phone_number' => $params['phone_number']]);
    }
}
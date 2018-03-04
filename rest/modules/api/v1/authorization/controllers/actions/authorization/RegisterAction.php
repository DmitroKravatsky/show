<?php

namespace rest\modules\api\v1\authorization\controllers\actions\authorization;

use rest\modules\api\v1\authorization\controllers\AuthorizationController;
use rest\modules\api\v1\authorization\models\RestUserEntity;
use yii\rest\Action;

/**
 * Class RegisterAction
 * @package rest\modules\api\v1\authorization\controllers\actions\authorization
 */
class RegisterAction extends Action
{
    /** @var  AuthorizationController */
    public $controller;

    /**
     * Register User action
     * 
     * @return array
     * @throws \yii\web\ServerErrorHttpException
     * @throws \yii\web\UnprocessableEntityHttpException
     */
    public function run()
    {
        /** @var RestUserEntity $model */
        $model = new $this->modelClass;
        /** @var RestUserEntity $user */
        $user = $model->register(\Yii::$app->request->bodyParams);

        return $this->controller->setResponse(
            201, 'Регистрация прошла успешно.', [
                'access_token'  => $user->getJWT(['user_id' => $user->id]),
                'refresh_token' => $user->refresh_token
            ]
        );
    }
}
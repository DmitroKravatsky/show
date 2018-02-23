<?php

namespace rest\modules\api\v1\authorization\controllers\actions\social;

use common\behaviors\ValidatePostParameters;
use rest\modules\api\v1\authorization\controllers\SocialController;
use rest\modules\api\v1\authorization\models\RestUserEntity;
use yii\rest\Action;

/**
 * Class FbLoginAction
 * @package rest\modules\api\v1\authorization\controllers\actions\social
 * @mixin ValidatePostParameters
 */
class FbLoginAction extends Action
{
    /** @var  SocialController */
    public $controller;
    
    /**
     * @var array
     */
    public $params = [];

    /**
     * @return array
     */
    public function behaviors(): array
    {
        return [
            'reportParams' => [
                'class'       => ValidatePostParameters::className(),
                'inputParams' => [
                    'token',
                ]
            ],
        ];
    }

    /**
     * @return bool
     * @throws \yii\web\BadRequestHttpException
     */
    protected function beforeRun(): bool
    {
        $this->validationParams();
        return parent::beforeRun();
    }

    /**
     * @return array
     * @throws \yii\web\NotFoundHttpException
     * @throws \yii\web\ServerErrorHttpException
     */
    public function run(): array
    {
        /** @var RestUserEntity $model */
        $model = new $this->modelClass;
        $user = $model->fbLogin(\Yii::$app->request->post('token'));

        return $this->controller->setResponse(
            200, 'Авторизация прошла успешно.', ['access_token' => $user->getJWT(['user_id' => $user->id])]
        );
    }
}
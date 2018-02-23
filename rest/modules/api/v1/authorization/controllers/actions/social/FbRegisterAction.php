<?php

namespace rest\modules\api\v1\authorization\controllers\actions\social;

use common\behaviors\ValidatePostParameters;
use rest\modules\api\v1\authorization\controllers\SocialController;
use yii\rest\Action;
use rest\modules\api\v1\authorization\models\RestUserEntity;

/**
 * Class FbRegisterAction
 * @package rest\modules\api\v1\authorization\controllers\actions\social
 *
 * @mixin ValidatePostParameters
 */
class FbRegisterAction extends Action
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
                    'token', 'terms_condition'
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
     * @throws \yii\web\ServerErrorHttpException
     * @throws \yii\web\UnprocessableEntityHttpException
     */
    public function run(): array
    {
        /** @var RestUserEntity $model */
        $model = new $this->modelClass;
        $user = $model->fbRegister(\Yii::$app->request->bodyParams);
        
        return $this->controller->setResponse(
            201, 'Регистрация прошла успешно.', ['access_token' => $user->getJWT(['user_id' => $user->id])]
        );
    }
}
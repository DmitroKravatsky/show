<?php

namespace rest\modules\api\v1\authorization\controllers\actions\social;

use common\behaviors\ValidatePostParameters;
use rest\modules\api\v1\authorization\controllers\SocialController;
use rest\modules\api\v1\authorization\models\RestUserEntity;
use yii\rest\Action;

/**
 * Class GmailRegisterAction
 * @package rest\modules\api\v1\authorization\controllers\actions\social
 * @mixin ValidatePostParameters
 */
class GmailRegisterAction extends Action
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
     * @return array|bool
     * @throws \yii\web\ServerErrorHttpException
     * @throws \yii\web\UnprocessableEntityHttpException
     */
    public function run()
    {
        /** @var RestUserEntity $model */
        $model = new $this->modelClass;
        $user = $model->gmailRegister(\Yii::$app->request->bodyParams);

        return $this->controller->setResponse(
            201, 'Регистрация прошла успешно.', ['access_token' => $user->getJWT(['user_id' => $user->id])]
        );
    }
}
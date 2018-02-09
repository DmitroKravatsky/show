<?php

namespace rest\modules\api\v1\authorization\controllers\actions\base;

use common\behaviors\ValidatePostParameters;
use rest\modules\api\v1\authorization\models\RestUserEntity;

/**
 * Class RegisterAction
 * @package rest\modules\api\v1\authorization\controllers\actions\base
 * @mixin ValidatePostParameters
 */
class RegisterAction extends \yii\rest\Action
{
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
                'inputParams' => ['password_hash', 'confirm_password']
            ]
        ];
    }

    /**
     * @return bool
     */
    protected function beforeRun(): bool
    {
        $this->validationParams();
        return parent::beforeRun();
    }

    /**
     * Register User action
     * @return array|bool
     * @throws \yii\web\ServerErrorHttpException
     * @throws \yii\web\UnprocessableEntityHttpException
     */
    public function run()
    {
        /** @var RestUserEntity $userModel */
        $userModel = new $this->modelClass;

        return $userModel->register(\Yii::$app->request->bodyParams);
    }
}
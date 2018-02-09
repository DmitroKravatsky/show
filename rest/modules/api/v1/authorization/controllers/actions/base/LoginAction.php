<?php

namespace rest\modules\api\v1\authorization\controllers\actions\base;

use rest\modules\api\v1\authorization\models\RestUserEntity;
use yii\rest\Action;
use common\behaviors\ValidatePostParameters;

/**
 * Class LoginAction
 * @package rest\modules\api\v1\authorization\controllers\actions\base
 * @mixin ValidatePostParameters
 */
class LoginAction extends Action
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
                'inputParams' => ['password_hash']
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
     * Login Action
     * @return array
     * @throws \yii\web\BadRequestHttpException
     * @throws \yii\web\NotFoundHttpException
     * @throws \yii\web\UnprocessableEntityHttpException
     */
    public function run(): array
    {
        /** @var RestUserEntity $userModel */
        $userModel = new $this->modelClass;

        return $userModel->login(\Yii::$app->request->bodyParams);
    }
}
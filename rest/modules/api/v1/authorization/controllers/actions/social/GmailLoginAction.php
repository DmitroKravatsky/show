<?php

namespace rest\modules\api\v1\authorization\controllers\actions\social;

use rest\modules\api\v1\authorization\models\RestUserEntity;
use yii\rest\Action;
use common\behaviors\ValidatePostParameters;

/**
 * Class GmailLoginAction
 * @package rest\modules\api\v1\authorization\controllers\actions\social
 * @mixin ValidatePostParameters
 */
class GmailLoginAction extends Action
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
        /** @var RestUserEntity $userModel */
        $userModel = new $this->modelClass;

        return $userModel->gmailLogin(\Yii::$app->request->post('token'));
    }
}
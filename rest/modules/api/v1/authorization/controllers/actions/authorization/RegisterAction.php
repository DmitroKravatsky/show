<?php

namespace rest\modules\api\v1\authorization\controllers\actions\authorization;

use rest\modules\api\v1\authorization\models\RestUserEntity;

/**
 * Class RegisterAction
 * @package rest\modules\api\v1\authorization\controllers\actions\authorization
 */
class RegisterAction extends \yii\rest\Action
{
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
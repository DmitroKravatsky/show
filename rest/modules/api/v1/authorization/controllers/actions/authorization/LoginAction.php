<?php

namespace rest\modules\api\v1\authorization\controllers\actions\authorization;

use rest\modules\api\v1\authorization\models\RestUserEntity;
use yii\rest\Action;

/**
 * Class LoginAction
 * @package rest\modules\api\v1\authorization\controllers\actions\authorization
 */
class LoginAction extends Action
{
    /**
     * Login action
     * @return array
     * @throws \yii\web\UnauthorizedHttpException
     */
    public function run(): array
    {
        /** @var RestUserEntity $userModel */
        $userModel = new $this->modelClass;

        return $userModel->login(\Yii::$app->request->bodyParams);
    }
}
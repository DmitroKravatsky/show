<?php

namespace rest\modules\api\v1\authorization\controllers\actions\authorization;

use rest\modules\api\v1\authorization\models\RestUserEntity;
use yii\rest\Action;

/**
 * Class LoginGuestAction
 * @package rest\modules\api\v1\authorization\controllers\actions\authorization
 */
class LoginGuestAction extends Action
{
    /**
     * @return mixed
     * @throws \yii\web\UnauthorizedHttpException
     */
    public function run()
    {
        /** @var RestUserEntity $userModel */
        $userModel = new $this->modelClass;
        return  $userModel->loginGuest();
    }
}
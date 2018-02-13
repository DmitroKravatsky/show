<?php

namespace rest\modules\api\v1\user\controllers\actions\profile;

use rest\modules\api\v1\authorization\models\RestUserEntity;
use yii\rest\Action;

/**
 * Class UpdatePasswordAction
 * @package rest\modules\api\v1\user\controllers\actions\profile
 */
class UpdatePasswordAction extends Action
{
    /**
     * Update User password
     * @return mixed
     */
    public function run()
    {
        $userModel = new RestUserEntity();
        return $userModel->updatePassword(\Yii::$app->request->bodyParams);
    }
}
<?php

namespace rest\modules\api\v1\user\controllers\actions\profile;

use rest\modules\api\v1\authorization\models\RestUserEntity;
use rest\modules\api\v1\user\controllers\UserProfileController;
use yii\rest\Action;

/**
 * Class UpdatePasswordAction
 * @package rest\modules\api\v1\user\controllers\actions\profile
 */
class UpdatePasswordAction extends Action
{
    /** @var  UserProfileController */
    public $controller;

    /**
     * Updates User password
     *
     * @return array
     */
    public function run(): array
    {
        $userModel = new RestUserEntity();
        $userModel->updatePassword(\Yii::$app->request->bodyParams);
        
        return $this->controller->setResponse(200, 'Пароль успешно изменён.');
    }
}
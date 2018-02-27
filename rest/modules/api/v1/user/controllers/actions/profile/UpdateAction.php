<?php

namespace rest\modules\api\v1\user\controllers\actions\profile;

use common\models\userProfile\UserProfileEntity;
use rest\modules\api\v1\user\controllers\UserProfileController;
use yii\rest\Action;
use Yii;
use yii\web\ServerErrorHttpException;

/**
 * Class UpdateAction
 * @package rest\modules\api\v1\user\controllers\actions\profile
 */
class UpdateAction extends Action
{
    /** @var  UserProfileController */
    public $controller;

    /**
     * Updates an existing model
     *
     * @return array
     * @throws ServerErrorHttpException
     * @throws \yii\web\UnprocessableEntityHttpException
     */
    public function run(): array 
    {
        /** @var UserProfileEntity $model */
        $model = new $this->modelClass;
        $userProfile = $model->updateProfile(Yii::$app->request->bodyParams);
        
        return $this->controller->setResponse(
            200, Yii::t('app', 'Профиль успешно изменён.'), $userProfile->getAttributes(['id', 'name', 'last_name', 'avatar']));
    }
}
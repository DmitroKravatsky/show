<?php

namespace rest\modules\api\v1\user\controllers\actions\profile;

use common\models\userProfile\UserProfileEntity;
use yii\rest\Action;
use Yii;
use yii\web\ServerErrorHttpException;

/**
 * Class UpdateAction
 * @package rest\modules\api\v1\user\controllers\actions\profile
 */
class UpdateAction extends Action
{
    /**
     * Updates an existing model
     * @return array|bool
     * @throws ServerErrorHttpException
     * @throws \yii\web\UnprocessableEntityHttpException
     */
    public function run()
    {
        /** @var UserProfileEntity $userProfile */
        $userProfile = new $this->modelClass;
        return $userProfile->updateProfile(\Yii::$app->request->bodyParams);
    }
}
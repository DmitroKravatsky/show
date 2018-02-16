<?php

namespace rest\modules\api\v1\user\controllers\actions\notifications;

use common\models\userNotifications\UserNotificationsEntity;
use yii\data\ArrayDataProvider;
use yii\rest\Action;

/**
 * Class ListAction
 * @package rest\modules\api\v1\user\controllers\actions\notifications
 */
class ListAction extends Action
{
    /**
     * @return \yii\data\ArrayDataProvider
     */
    public function run(): ArrayDataProvider
    {
        /** @var UserNotificationsEntity $userNotifications */
        $userNotifications = new $this->modelClass;
        return $userNotifications->getUserNotificationsByUser(\Yii::$app->user->id);
    }
}
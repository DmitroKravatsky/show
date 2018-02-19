<?php

namespace rest\modules\api\v1\user\controllers\actions\notifications;


use common\models\userNotifications\UserNotificationsEntity;
use yii\rest\Action;

class DeleteAction extends Action
{
    /**
     * @param $id
     * @return array
     * @throws \yii\web\ServerErrorHttpException
     */
    public function run($id): array
    {
        /** @var UserNotificationsEntity $userNotificationsModel */
        $userNotificationsModel = new $this->modelClass;
        return $userNotificationsModel->deleteNotify($id);
    }
}
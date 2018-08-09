<?php

namespace backend\modules\admin\controllers\actions\notifications;

use backend\modules\admin\controllers\NotificationsController;
use common\models\userNotifications\UserNotificationsEntity;
use yii\base\Action;

class ViewAction extends Action
{
    /** @var NotificationsController */
    public $controller;

    /** @var UserNotificationsEntity */
    public $notification;

    /**
     * @param $id
     * @return string
     * @throws \yii\web\NotFoundHttpException
     */
    public function run($id)
    {
        $this->notification = $this->controller->findNotification($id);
        return $this->controller->render('view', [
            'notification' => $this->notification,
        ]);
    }

    protected function afterRun()
    {
        $this->notification->status = UserNotificationsEntity::STATUS_READ;
        $this->notification->save(false);
    }
}

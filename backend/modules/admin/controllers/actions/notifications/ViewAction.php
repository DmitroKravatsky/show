<?php

namespace backend\modules\admin\controllers\actions\notifications;

use backend\modules\admin\controllers\NotificationsController;
use common\models\userNotifications\UserNotifications;
use yii\base\Action;
use Yii;

class ViewAction extends Action
{
    /** @var NotificationsController */
    public $controller;

    /** @var UserNotifications */
    public $userNotification;

    /**
     * @param int $id
     * @return string
     * @throws \yii\web\NotFoundHttpException
     */
    public function run($id)
    {
        $this->userNotification = $this->controller->findUserNotification($id, Yii::$app->user->id);
        return $this->controller->render('view', [
            'notification' => $this->userNotification->notification,
        ]);
    }

    protected function afterRun()
    {
        $this->userNotification->is_read = UserNotifications::STATUS_READ_YES;
        $this->userNotification->save(false, ['is_read']);
    }
}

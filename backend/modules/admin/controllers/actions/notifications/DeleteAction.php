<?php

namespace backend\modules\admin\controllers\actions\notifications;

use backend\modules\admin\controllers\NotificationsController;
use yii\base\Action;
use Yii;

class DeleteAction extends Action
{
    /** @var NotificationsController */
    public $controller;

    /**
     * @param int $id
     * @return \yii\web\Response
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
     * @throws \yii\web\NotFoundHttpException
     */
    public function run($id)
    {
        $notification = $this->controller->findNotification($id, Yii::$app->user->id);
        if ($notification->delete()) {
            Yii::$app->session->setFlash('success', Yii::t('app', 'Notification successfully deleted.'));
        } else {
            Yii::$app->session->setFlash('error', Yii::t('app', 'Something wrong, please try again later.'));
        }

        return $this->controller->redirect('index');
    }
}

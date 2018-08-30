<?php

namespace backend\modules\admin\controllers\actions\notifications;

use yii\base\Action;
use yii\helpers\Url;
use common\models\userNotifications\UserNotificationsEntity as Notification;
use Yii;
use yii\web\ErrorHandler;
use yii\web\ServerErrorHttpException;

class ReadAllAction extends Action
{
    /**
     * @return \yii\web\Response
     * @throws ServerErrorHttpException
     */
    public function run()
    {
        try {
            Notification::updateAll(['status' => Notification::STATUS_READ], ['recipient_id' => Yii::$app->user->id]);
            Yii::$app->session->setFlash('success', Yii::t('app', 'Status updated successfully.'));
            return $this->controller->redirect(Url::to(['notifications/index']));
        } catch (\Exception $e) {
            Yii::$app->session->setFlash('error', Yii::t('app', 'Something wrong, please try again later.'));
            ErrorHandler::convertExceptionToString($e);
            throw new ServerErrorHttpException($e->getMessage());
        }
    }
}

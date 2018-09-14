<?php

namespace backend\modules\admin\controllers\actions\notifications;

use common\models\userNotifications\UserNotifications;
use yii\base\Action;
use Yii;
use yii\helpers\Url;
use yii\web\ErrorHandler;
use yii\web\ServerErrorHttpException;

class DeleteAllAction extends Action
{
    /**
     * @return \yii\web\Response
     * @throws ServerErrorHttpException
     */
    public function run()
    {
        try{
            UserNotifications::deleteAll(['user_id' => Yii::$app->user->id]);
            Yii::$app->session->setFlash('success', Yii::t('app', 'Notification successfully deleted.'));
            return $this->controller->redirect(Url::to(['notifications/index']));
        } catch (\Exception $e) {
            Yii::$app->session->setFlash('error', Yii::t('app', 'Something wrong, please try again later.'));
            ErrorHandler::convertExceptionToString($e);
            throw new ServerErrorHttpException($e->getMessage());
        }
    }
}

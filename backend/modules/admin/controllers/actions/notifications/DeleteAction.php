<?php

namespace backend\modules\admin\controllers\actions\notifications;

use Yii;
use backend\modules\admin\controllers\NotificationsController;
use yii\base\Action;
use common\models\userNotifications\UserNotificationsSearch;

class DeleteAction extends Action
{
    /** @var NotificationsController */
    public $controller;

    public function run($id)
    {
        if (Yii::$app->request->isAjax) {
            $this->controller->findUserNotification($id, Yii::$app->user->id)->delete();
        }

        $searchModel = new UserNotificationsSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->controller->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }
}

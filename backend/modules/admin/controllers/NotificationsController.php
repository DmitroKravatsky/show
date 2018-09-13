<?php

namespace backend\modules\admin\controllers;

use common\models\userNotifications\NotificationsEntity;
use common\models\userNotifications\UserNotifications;
use yii\web\Controller;
use yii\filters\AccessControl;
use backend\modules\admin\controllers\actions\notifications\{
    IndexAction,
    ViewAction,
    DeleteAction,
    ReadAllAction,
    DeleteAllAction
};
use Yii;
use yii\web\NotFoundHttpException;

class NotificationsController extends Controller
{
    /**
     * @return array
     */
    public function behaviors(): array
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'allow'   => true,
                        'actions' => ['index', 'view', 'delete', 'read-all', 'delete-all',],
                        'roles'   => ['admin', 'manager',]
                    ],
                ],
            ],
        ];
    }

    /**
     * @return array
     */
    public function actions(): array
    {
        return [
            'index' => [
                'class' => IndexAction::class
            ],
            'view' => [
                'class' => ViewAction::class
            ],
            'delete' => [
                'class' => DeleteAction::class
            ],
            'read-all' => [
                'class' => ReadAllAction::class
            ],
            'delete-all' => [
                'class' => DeleteAllAction::class
            ],
        ];
    }

    /**
     * @param int $notificationId
     * @param int $userId
     * @return UserNotifications|null
     * @throws NotFoundHttpException
     */
    public function findUserNotification($notificationId, $userId)
    {
        $userNotification = UserNotifications::findOne(['notification_id' => $notificationId, 'user_id' => $userId]);
        if ($userNotification !== null) {
            return $userNotification;
        }
        throw new NotFoundHttpException(Yii::t('app', 'Notification not found'));
    }
}

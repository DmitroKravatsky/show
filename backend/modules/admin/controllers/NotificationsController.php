<?php

namespace backend\modules\admin\controllers;

use common\models\userNotifications\UserNotificationsEntity;
use yii\web\Controller;
use yii\filters\AccessControl;
use backend\modules\admin\controllers\actions\notifications\{
    IndexAction,
    ViewAction,
    DeleteAction
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
                        'actions' => ['index', 'view', 'delete',],
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
        ];
    }

    /**
     * @param int $id
     * @param int $recipientId
     * @return UserNotificationsEntity|null
     * @throws NotFoundHttpException
     */
    public function findNotification($id, $recipientId)
    {
        $notification = UserNotificationsEntity::findOne(['id' => $id, 'recipient_id' => $recipientId]);
        if ($notification !== null) {
            return $notification;
        }
        throw new NotFoundHttpException(Yii::t('app', 'Notification not found'));
    }
}

<?php

namespace rest\modules\api\v1\user\controllers\actions\notifications;

use common\models\userNotifications\UserNotificationsEntity;
use rest\modules\api\v1\user\controllers\UserNotificationsController;
use yii\rest\Action;
use yii\web\NotFoundHttpException;
use yii\web\ServerErrorHttpException;
use Yii;

class DeleteAction extends Action
{
    /** @var  UserNotificationsController */
    public $controller;

    /**
     * Deletes an existing UserNotificationsEntity
     *
     * @param $id
     * @return array
     * @throws NotFoundHttpException
     * @throws ServerErrorHttpException
     */
    public function run($id): array
    {
        try {
            /** @var UserNotificationsEntity $userNotificationsModel */
            $userNotificationsModel = new $this->modelClass;
            if ($userNotificationsModel->deleteNotify($id)) {
                return $this->controller->setResponse(200, 'Уведомление успешно удалено.');
            }
            throw new ServerErrorHttpException;
        } catch (NotFoundHttpException $e) {
            throw new NotFoundHttpException($e->getMessage());
        } catch (\Exception $e) {
            Yii::error($e->getMessage());
            throw new ServerErrorHttpException('Произошла ошибка при удалении уведомления.');
        }
    }
}
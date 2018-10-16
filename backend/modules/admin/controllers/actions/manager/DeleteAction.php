<?php

namespace backend\modules\admin\controllers\actions\manager;

use backend\models\BackendUser;
use backend\modules\admin\controllers\ManagerController;
use yii\base\Action;
use yii\helpers\Url;
use yii\web\ErrorHandler;
use yii\web\ServerErrorHttpException;
use Yii;

/**
 * Class DeleteAction
 * @package backend\modules\admin\controllers\actions\admin
 */
class DeleteAction extends Action
{
    /** @var ManagerController */
    public $controller;

    /**
     * Delete manager
     * @param $userId integer Manager id that will be deleted
     * @return \yii\web\Response
     * @throws ServerErrorHttpException
     * @throws \Throwable
     * @throws \yii\web\NotFoundHttpException
     */
    public function run($userId)
    {
        if(BackendUser::managerHasInprogressBid($userId)) {
            Yii::$app->session->setFlash('error',
                Yii::t('app', 'Manager has at least one bid in process. Deletion is not allowed')
            );
            return $this->controller->redirect(Url::to(\Yii::$app->request->referrer));
        }

        $user = $this->controller->findModel($userId);
        try {
            $user->delete();
            Yii::$app->session->setFlash('success', Yii::t('app', 'Manager successfully deleted.'));
        } catch (\Exception $e) {
            Yii::error(ErrorHandler::convertExceptionToString($e));
            Yii::$app->session->setFlash('error', Yii::t('app', 'Something wrong, please try again later.'));
            throw new ServerErrorHttpException();
        }
        return $this->controller->redirect(Url::to(\Yii::$app->request->referrer));
    }
}

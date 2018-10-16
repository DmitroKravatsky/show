<?php

namespace backend\modules\admin\controllers\actions\reserve;

use backend\modules\admin\controllers\ReserveController;
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
    /** @var ReserveController */
    public $controller;

    /**
     * Delete manager
     * @param $id integer Reserve id that will be deleted
     * @return \yii\web\Response
     * @throws ServerErrorHttpException
     * @throws \Throwable
     * @throws \yii\web\NotFoundHttpException
     */
    public function run($id)
    {
        $reserve = $this->controller->findModel($id);
        try {
            $reserve->delete();
            Yii::$app->session->setFlash('success', Yii::t('app', 'Reserve successfully deleted.'));
        } catch (\Exception $e) {
            Yii::error(ErrorHandler::convertExceptionToString($e));
            Yii::$app->session->setFlash('error', Yii::t('app', 'Something wrong, please try again later.'));
            throw new ServerErrorHttpException();
        }
        return $this->controller->redirect(Url::to(\Yii::$app->request->referrer));
    }
}

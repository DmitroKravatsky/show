<?php

namespace backend\modules\admin\controllers\actions\review;

use backend\modules\admin\controllers\ReviewController;
use yii\base\Action;
use yii\helpers\Url;
use yii\web\ErrorHandler;
use yii\web\ServerErrorHttpException;
use Yii;

/**
 * Class DeleteAction
 * @package backend\modules\admin\controllers\actions\review
 */
class DeleteAction extends Action
{
    /** @var ReviewController */
    public $controller;

    /**
     * Delete review
     * @param $id integer Review id that will be deleted
     * @return \yii\web\Response
     * @throws ServerErrorHttpException
     * @throws \Throwable
     * @throws \yii\web\NotFoundHttpException
     */
    public function run($id)
    {
        $review = $this->controller->findModel($id);
        try {
            $review->delete();
            Yii::$app->session->setFlash('success', Yii::t('app', 'Review successfully deleted.'));
        } catch (\Exception $e) {
            Yii::error(ErrorHandler::convertExceptionToString($e));
            Yii::$app->session->setFlash('error', Yii::t('app', 'Something wrong, please try again later.'));
            throw new ServerErrorHttpException();
        }
        return $this->controller->redirect(Url::to(\Yii::$app->request->referrer));
    }
}

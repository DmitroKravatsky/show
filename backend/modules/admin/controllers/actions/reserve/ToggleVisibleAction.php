<?php

namespace backend\modules\admin\controllers\actions\reserve;

use backend\modules\admin\controllers\ReserveController;
use yii\base\Action;
use Yii;

class ToggleVisibleAction extends Action
{
    /** @var ReserveController */
    public $controller;

    /**
     * @param integer $id
     * @return \yii\web\Response
     * @throws \yii\web\NotFoundHttpException
     */
    public function run($id)
    {
        $reserve = $this->controller->findModel($id);
        if ($reserve->toggleVisible()) {
            Yii::$app->session->setFlash('success', Yii::t('app', 'Reserve successfully updated.'));
        } else {
            Yii::$app->session->setFlash('error', Yii::t('app', 'Something wrong, please try again later.'));
        }

        return $this->controller->redirect(['index']);
    }
}

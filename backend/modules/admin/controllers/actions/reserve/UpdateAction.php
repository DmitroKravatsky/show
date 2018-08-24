<?php

namespace backend\modules\admin\controllers\actions\reserve;

use backend\modules\admin\controllers\ReserveController;
use yii\base\Action;
use Yii;
use yii\helpers\Url;

class UpdateAction extends Action
{
    /** @var ReserveController */
    public $controller;

    /**
     * @param integer $id
     * @return string|\yii\web\Response
     * @throws \yii\web\NotFoundHttpException
     */
    public function run($id)
    {
        $reserve = $this->controller->findModel($id);
        if ($reserve->load(Yii::$app->request->post()) && $reserve->save()) {
            Yii::$app->session->setFlash('success', Yii::t('app', 'Reserve successfully updated.'));
            return $this->controller->redirect(Url::to(['index']));
        }

        return $this->controller->render('update', [
            'reserve' => $reserve,
        ]);
    }
}

<?php

namespace backend\modules\admin\controllers\actions\paymentSystem;

use backend\modules\admin\controllers\PaymentSystemController;
use yii\base\Action;
use Yii;
use yii\helpers\Url;

class ToggleVisibleAction extends Action
{
    /** @var PaymentSystemController */
    public $controller;

    /**
     * @param integer $id
     * @return \yii\web\Response
     * @throws \yii\web\NotFoundHttpException
     */
    public function run($id)
    {
        $paymentSystem = $this->controller->findModel($id);
        if ($paymentSystem->toggleVisible()) {
            Yii::$app->session->setFlash('success', Yii::t('app', 'Payment System successfully updated.'));
        } else {
            Yii::$app->session->setFlash('error', Yii::t('app', 'Something wrong, please try again later.'));
        }

        return $this->controller->redirect(Url::to(Yii::$app->request->referrer));
    }
}

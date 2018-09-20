<?php

namespace backend\modules\admin\controllers\actions\paymentSystem;

use backend\modules\admin\controllers\PaymentSystemController;
use Yii;
use yii\base\Action;
use yii\helpers\Url;
use yii\web\NotFoundHttpException;

/**
 * Class DeleteAction
 * @package backend\modules\admin\controllers\actions\bid
 */
class DeleteAction extends Action
{
    /** @var PaymentSystemController */
    public $controller;

    /**
     * Deletes a payment system
     * @param integer $id
     * @return bool|\yii\web\Response
     * @throws NotFoundHttpException
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
     */
    public function run($id)
    {
        $paymentSystem = $this->controller->findModel($id);
        if ($paymentSystem->delete()) {
            Yii::$app->session->setFlash('success', Yii::t('app', 'Payment System successfully deleted.'));
            return $this->controller->redirect(Url::to(Yii::$app->request->referrer));
        }
        Yii::$app->session->setFlash('error', Yii::t('app', 'Something wrong, please try again later.'));
        return $this->controller->redirect(Url::to(Yii::$app->request->referrer));
    }

}

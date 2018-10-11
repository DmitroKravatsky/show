<?php

namespace backend\modules\admin\controllers\actions\paymentSystem;

use backend\modules\admin\controllers\ManagerController;
use backend\modules\admin\controllers\PaymentSystemController;
use yii\base\Action;
use yii\helpers\Url;
use yii\web\ErrorHandler;
use yii\web\ServerErrorHttpException;
use Yii;

/**
 * Class DeleteAction
 */
class DeleteAction extends Action
{
    /** @var PaymentSystemController */
    public $controller;

    /**
     * Delete an existing Payment system model
     * @param integer $id
     * @return \yii\web\Response
     */
    public function run($id)
    {
        $paymentSystem = $this->controller->findModel($id);
        if ($paymentSystem->delete()) {
            Yii::$app->session->setFlash('success', Yii::t('app', 'Payment system successfully deleted.'));
        } else {
            Yii::$app->session->setFlash('error', Yii::t('app', 'Something wrong, please try again later.'));
        }

        return $this->controller->redirect(Url::to(\Yii::$app->request->referrer));
    }
}
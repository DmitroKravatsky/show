<?php

namespace backend\modules\admin\controllers\actions\paymentSystem;

use backend\modules\admin\controllers\PaymentSystemController;
use yii\base\Action;
use Yii;
use yii\helpers\Url;

class UpdateAction extends Action
{
    /** @var PaymentSystemController */
    public $controller;

    /**
     * @param integer $id
     * @return string|\yii\web\Response
     * @throws \yii\web\NotFoundHttpException
     */
    public function run($id)
    {
        //Yii::$app->language = Yii::$app->session->get('language', Yii::$app->language);
        $paymentSystem = $this->controller->findModel($id);
        if ($paymentSystem->load(Yii::$app->request->post()) && $paymentSystem->save()) {
            Yii::$app->session->setFlash('success', Yii::t('app', 'Payment System successfully updated.'));
            return $this->controller->redirect(Url::to(['index']));
        }

        return $this->controller->render('update', [
            'paymentSystem' => $paymentSystem,
        ]);
    }
}

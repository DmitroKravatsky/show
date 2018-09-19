<?php

namespace backend\modules\admin\controllers\actions\paymentSystem;

use backend\modules\admin\controllers\PaymentSystemController;
use common\models\paymentSystem\PaymentSystem;
use yii\base\Action;
use Yii;
use yii\helpers\Url;

class CreateAction extends Action
{
    /** @var PaymentSystemController */
    public $controller;

    /**
     * Creates a new Payment System model
     * @return string|\yii\web\Response
     */
    public function run()
    {
        $paymentSystem = new PaymentSystem();
        if ($paymentSystem->load(Yii::$app->request->post()) && $paymentSystem->save()) {
            Yii::$app->session->setFlash('success', Yii::t('app', 'Payment System successfully created.'));
            return $this->controller->redirect(Url::to(['index']));
        }

        return $this->controller->render('create', [
            'paymentSystem' => $paymentSystem,
        ]);
    }
}

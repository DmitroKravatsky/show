<?php

namespace backend\modules\admin\controllers\actions\paymentSystem;

use backend\modules\admin\controllers\PaymentSystemController;
use yii\base\Action;

class ViewAction extends Action
{
    /**
     * @var PaymentSystemController
     */
    public $controller;

    /**
     * @param integer $id
     * @return string
     * @throws \yii\web\NotFoundHttpException
     */
    public function run($id): string
    {
        $paymentSystem = $this->controller->findModel($id);

        return $this->controller->render('view', [
            'paymentSystem' => $paymentSystem,
        ]);
    }
}

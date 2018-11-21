<?php

namespace backend\modules\admin\controllers\actions\paymentSystem;

use Yii;
use backend\modules\admin\controllers\PaymentSystemController;
use yii\base\Action;
use common\models\paymentSystem\PaymentSystemSearch;

class DeleteAction extends Action
{
    /** @var PaymentSystemController */
    public $controller;

    public function run($id)
    {
        if (Yii::$app->request->isAjax) {
            $this->controller->findModel($id)->delete();
        }

        $searchModel = new PaymentSystemSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->controller->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }
}

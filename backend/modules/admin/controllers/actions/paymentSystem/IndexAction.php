<?php

namespace backend\modules\admin\controllers\actions\paymentSystem;

use common\models\paymentSystem\PaymentSystemSearch;
use yii\base\Action;
use Yii;

class IndexAction extends Action
{
    /**
     * Returns list of payment systems
     * @return string
     */
    public function run(): string
    {
        $searchModel = new PaymentSystemSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->controller->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }
}

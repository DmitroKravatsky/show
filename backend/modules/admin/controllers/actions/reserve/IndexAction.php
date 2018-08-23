<?php

namespace backend\modules\admin\controllers\actions\reserve;

use common\models\reserve\ReserveEntitySearch;
use yii\base\Action;
use Yii;

class IndexAction extends Action
{
    public function run()
    {
        $searchModel = new ReserveEntitySearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->controller->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }
}

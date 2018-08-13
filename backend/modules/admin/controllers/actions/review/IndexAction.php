<?php

namespace backend\modules\admin\controllers\actions\review;

use common\models\review\ReviewSearch;
use yii\base\Action;
use Yii;

class IndexAction extends Action
{
    /**
     * Returns list of reviews
     * @return string
     */
    public function run(): string
    {
        $searchModel = new ReviewSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->controller->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }
}

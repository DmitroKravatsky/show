<?php

namespace backend\modules\admin\controllers\actions\review;

use Yii;
use backend\modules\admin\controllers\ReviewController;
use yii\base\Action;
use common\models\review\ReviewEntity;
use common\models\review\ReviewSearch;

class DeleteAction extends Action
{
    /** @var ReviewController */
    public $controller;

    public function run($id)
    {
        if (Yii::$app->request->isAjax) {
            $this->controller->findModel($id)->delete();
        }

        $newReviewModel = new ReviewEntity();
        $searchModel = new ReviewSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->controller->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'newReviewModel' => $newReviewModel,
        ]);
    }
}

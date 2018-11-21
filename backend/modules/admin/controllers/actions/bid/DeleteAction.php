<?php

namespace backend\modules\admin\controllers\actions\bid;

use Yii;
use backend\modules\admin\controllers\BidController;
use yii\base\Action;
use common\models\bid\BidSearch;

class DeleteAction extends Action
{
    /** @var  BidController */
    public $controller;

    public function run($id)
    {
        if (Yii::$app->request->isAjax) {
            $this->controller->findBid($id)->delete();
        }

        $searchModel = new BidSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->controller->render('index', [
            'dataProvider'  => $dataProvider,
            'searchModel'  => $searchModel,
        ]);
    }
}

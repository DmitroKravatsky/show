<?php

namespace backend\modules\admin\controllers\actions\bid;

use backend\modules\admin\controllers\BidController;
use common\models\bid\BidEntity;
use yii\base\Action;
use yii\data\ArrayDataProvider;
use yii\data\Pagination;

class IndexAction extends Action
{
    /** @var  BidController */
    public $controller;
    public function run()
    {
        $model = BidEntity::find();
        $countQuery = clone $model;
        $pages = new Pagination(['totalCount' => $countQuery->count()]);
        $bids = $model->offset($pages->offset)
            ->limit($pages->limit)
            ->all();
//        echo '<pre>';var_dump($models); exit;

        return $this->controller->render('index', [
            'bids' => $bids,
            'pages' => $pages,
        ]);
        return $this->controller->render('index', [
            'bids' => $dataProvider
        ]);
    }
}
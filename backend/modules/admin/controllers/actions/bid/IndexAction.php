<?php

namespace backend\modules\admin\controllers\actions\bid;

use backend\modules\admin\controllers\BidController;
use common\models\bid\BidEntity;
use yii\base\Action;
use yii\data\ArrayDataProvider;
use yii\data\Pagination;

/**
 * Class IndexAction
 * @package backend\modules\admin\controllers\actions\bid
 */
class IndexAction extends Action
{
    /** @var  BidController */
    public $controller;

    /**
     * View a list of all bids
     * @return string
     */
    public function run()
    {
        $model = BidEntity::find();
        $countQuery = clone $model;
        $pages = new Pagination(['totalCount' => $countQuery->count()]);
        $bids = $model->offset($pages->offset)
            ->limit($pages->limit)
            ->all();

        return $this->controller->render('index', [
            'bids'  => $bids,
            'pages' => $pages,
        ]);
    }
}
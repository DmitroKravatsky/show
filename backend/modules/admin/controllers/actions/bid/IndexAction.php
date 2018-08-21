<?php

namespace backend\modules\admin\controllers\actions\bid;

use backend\modules\admin\controllers\BidController;
use common\models\bid\BidSearch;
use yii\base\Action;

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
        $searchModel = new BidSearch();

        $dataProvider = $searchModel->search(\Yii::$app->request->queryParams);

        return $this->controller->render('index', [
            'dataProvider'  => $dataProvider,
            'searchModel'  => $searchModel,
        ]);
    }
}

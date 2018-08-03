<?php

namespace backend\modules\admin\controllers\actions\bid;

use backend\modules\admin\controllers\BidController;
use common\models\bid\BidEntity;
use backend\modules\admin\models\BidEntitySearch;
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
        $searchModel = new BidEntitySearch();

        $dataProvider = $searchModel->search(\Yii::$app->request->queryParams);

        return $this->controller->render('index', [
            'dataProvider'  => $dataProvider,
            'searchModel'  => $searchModel,
        ]);
    }
}
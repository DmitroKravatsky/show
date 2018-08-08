<?php

namespace backend\modules\admin\controllers\actions\bidHistory;

use backend\modules\admin\controllers\BidController;
use yii\base\Action;
use common\models\bidHistory\BidHistorySearch;

/**
 * Class IndexAction
 * @package backend\modules\admin\controllers\actions\bidHistory
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
        $searchModel = new BidHistorySearch();
        $dataProvider = $searchModel->search(\Yii::$app->request->queryParams);

        return $this->controller->render('index', [
            'dataProvider'  => $dataProvider,
            'searchModel'  => $searchModel,
        ]);
    }
}

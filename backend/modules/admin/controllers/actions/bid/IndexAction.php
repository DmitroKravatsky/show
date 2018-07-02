<?php

namespace backend\modules\admin\controllers\actions\bid;

use backend\modules\admin\controllers\BidController;
use common\models\bid\BidEntity;
use yii\base\Action;
use yii\data\ActiveDataProvider;
use yii\data\ArrayDataProvider;
use yii\data\Pagination;
use yii\debug\models\timeline\DataProvider;

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
        $bids = BidEntity::find()->select('created_by', 'from_payment_system',
            'to_payment_system', 'from_wallet', 'to_wallet', 'from_currency', 'to_currency',
            'from_sum', 'to_sum', 'created_at', 'updated_at');
        $dataProvider = new ActiveDataProvider([
            'query' => $bids
        ]);
//        echo '<pre>' ;var_dump($dataProvider); exit;

        return $this->controller->render('index', [
            'dataProvider'  => $dataProvider,
        ]);
    }
}
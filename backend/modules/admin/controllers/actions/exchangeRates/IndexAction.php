<?php

namespace backend\modules\admin\controllers\actions\exchangeRates;

use backend\modules\admin\controllers\BidController;
use common\models\exchangeRates\ExchangeRatesSearch;
use yii\base\Action;
use Yii;
use yii\helpers\Json;
use common\models\exchangeRates\ExchangeRates;

/**
 * Class IndexAction
 * @package backend\modules\admin\controllers\actions\exchangeRates
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
        $searchModel = new ExchangeRatesSearch();
        $dataProvider = $searchModel->search(\Yii::$app->request->queryParams);

        if (Yii::$app->request->post('hasEditable')) {
            $exchangeRates = ExchangeRates::findOne(Yii::$app->request->post('editableKey'));
            $exchangeRates->value = current(Yii::$app->request->post('ExchangeRates'))['value'] ?? 0;
            $exchangeRates->save(false, ['value']);
            return  Json::encode(['output' => round($exchangeRates->value, 2)]);
        }

        return $this->controller->render('index', [
            'dataProvider'  => $dataProvider,
            'searchModel'  => $searchModel,
        ]);
    }
}

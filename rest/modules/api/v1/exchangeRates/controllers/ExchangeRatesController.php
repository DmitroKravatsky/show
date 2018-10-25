<?php

namespace rest\modules\api\v1\exchangeRates\controllers;

use common\models\exchangeRates\ExchangeRates;
use rest\modules\api\v1\exchangeRates\controllers\actions\CalculateAmountAction;
use rest\modules\api\v1\exchangeRates\controllers\actions\ListAction;
use yii\rest\Controller;
use yii\filters\VerbFilter;

/**
 * Class ExchangeRatesController
 * @package rest\modules\api\v1\exchangeRates\controllers
 */
class ExchangeRatesController extends Controller
{
    /** @var ExchangeRates $modelClass */
    public $modelClass = ExchangeRates::class;

    /**
     * @return array
     */
    public function behaviors(): array
    {
        $behaviors = parent::behaviors();

        $behaviors['verbs'] = [
            'class'   => VerbFilter::class,
            'actions' => [
                'list'             => ['GET'],
                'calculate-amount' => ['POST'],
            ]
        ];

        return $behaviors;
    }

    /**
     * @return array
     */
    public function actions()
    {
        $actions = parent::actions();

        $actions['list'] = [
            'class'      => ListAction::class,
            'modelClass' => $this->modelClass
        ];
        $actions['calculate-amount'] = [
            'class'      => CalculateAmountAction::class,
            'modelClass' => $this->modelClass
        ];

        return $actions;
    }
}

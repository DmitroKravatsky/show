<?php

namespace backend\modules\admin\controllers;

use backend\modules\admin\controllers\actions\exchangeRates\ExportAction;
use yii\filters\AccessControl;
use yii\web\Controller;
use backend\modules\admin\controllers\actions\exchangeRates\IndexAction;

class ExchangeRatesController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'allow'   => true,
                        'actions' => ['index', 'export',],
                        'roles'   => ['admin', 'manager',]
                    ],
                ],
            ],
        ];
    }

    public function actions()
    {
        return [
            'index'  => [
                'class' => IndexAction::class
            ],
            'export'  => [
                'class' => ExportAction::class
            ],
        ];
    }
}

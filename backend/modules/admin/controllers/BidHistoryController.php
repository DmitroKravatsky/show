<?php

namespace backend\modules\admin\controllers;

use backend\modules\admin\controllers\actions\bidHistory\IndexAction;
use yii\web\Controller;
use yii\filters\AccessControl;

class BidHistoryController extends Controller
{
    /**
     * @return array
     */
    public function behaviors(): array
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'allow'   => true,
                        'actions' => ['index',],
                        'roles'   => ['admin', 'manager',]
                    ],
                ],
            ],
        ];
    }

    /**
     * @return array
     */
    public function actions(): array
    {
        return [
            'index' => [
                'class' => IndexAction::class
            ],
        ];
    }
}

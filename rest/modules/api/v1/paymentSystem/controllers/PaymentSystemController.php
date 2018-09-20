<?php

namespace rest\modules\api\v1\paymentSystem\controllers;

use common\models\paymentSystem\PaymentSystem;
use yii\rest\Controller;
use yii\filters\VerbFilter;
use rest\modules\api\v1\paymentSystem\controllers\actions\ListAction;

class PaymentSystemController extends Controller
{
    public $modelClass = PaymentSystem::class;

    /**
     * @return array
     */
    public function behaviors(): array
    {
        $behaviors = parent::behaviors();

        $behaviors['verbs'] = [
            'class'   => VerbFilter::class,
            'actions' => [
                'list'   => ['GET'],
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

        $actions['list'] =   [
            'class'      => ListAction::class,
            'modelClass' => $this->modelClass
        ];

        return $actions;
    }
}

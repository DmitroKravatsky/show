<?php

namespace rest\modules\api\v1\reserve\controllers;

use common\models\reserve\ReserveEntity;
use rest\modules\api\v1\reserve\controllers\actions\ListAction;
use yii\rest\Controller;
use yii\filters\VerbFilter;

/**
 * Class ReserveController
 * @package rest\modules\api\v1\reserve\controllers
 */
class ReserveController extends Controller
{
    /**
     * @var ReserveEntity $modelClass
     */
    public $modelClass = ReserveEntity::class;

    public $serializer = [
        'class' => 'yii\rest\Serializer',
        'collectionEnvelope' => 'items'
    ];

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
    public function actions(): array
    {
        return [
            'list' => [
                'class' => ListAction::class,
                'modelClass' => $this->modelClass,
            ],
        ];
    }
}
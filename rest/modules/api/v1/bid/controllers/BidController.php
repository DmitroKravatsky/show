<?php

namespace rest\modules\api\v1\bid\controllers;

use common\models\bid\BidEntity;
use rest\behaviors\ResponseBehavior;
use rest\modules\api\v1\bid\controllers\actions\{
    ListAction, CreateAction, DeleteAction, UpdateAction, DetailAction
};
use yii\filters\auth\HttpBearerAuth;
use yii\rest\Controller;
use yii\filters\VerbFilter;

/**
 * Class BidController
 * @package rest\modules\api\v1\bid\controllers
 *
 * @mixin ResponseBehavior
 */
class BidController extends Controller
{
    /** @var BidEntity $modelClass */
    public $modelClass = BidEntity::class;

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

        $behaviors['bearerAuth'] = [
            'class' => HttpBearerAuth::class,
        ];

        $behaviors['verbs'] = [
            'class'   => VerbFilter::class,
            'actions' => [
                'create' => ['POST'],
                'delete' => ['DELETE'],
                'list'   => ['GET'],
                'detail' => ['GET'],
            ]
        ];

        $behaviors['responseBehavior'] = ResponseBehavior::class;

        return $behaviors;
    }

    /**
     * @return array
     */
    public function actions()
    {
        $actions = parent::actions();

        $actions['create'] = [
            'class'      => CreateAction::class,
            'modelClass' => $this->modelClass
        ];

        $actions['delete'] = [
            'class'      => DeleteAction::class,
            'modelClass' => $this->modelClass
        ];

        $actions['list'] =   [
            'class'      => ListAction::class,
            'modelClass' => $this->modelClass
        ];

        $actions['detail'] = [
            'class'      => DetailAction::class,
            'modelClass' => $this->modelClass
        ];

        return $actions;
    }
}
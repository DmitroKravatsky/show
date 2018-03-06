<?php

namespace rest\modules\api\v1\reserve\controllers;

use common\models\reserve\ReserveEntity;
use rest\behaviors\ResponseBehavior;
use rest\modules\api\v1\reserve\controllers\actions\CreateAction;
use rest\modules\api\v1\reserve\controllers\actions\ListAction;
use rest\modules\api\v1\reserve\controllers\actions\UpdateAction;
use yii\rest\Controller;
use yii\filters\VerbFilter;

/**
 * Class ReserveController
 * @package rest\modules\api\v1\reserve\controllers
 * 
 * @mixin ResponseBehavior
 */
class ReserveController extends Controller
{
    /**
     * @var ReserveEntity $modelClass
     */
    public $modelClass = ReserveEntity::class;

    /**
     * @return array
     */
    public function behaviors(): array
    {
        $behaviors = parent::behaviors();

        $behaviors['verbs'] = [
            'class'   => VerbFilter::class,
            'actions' => [
                'update' => ['PUT'],
                'create' => ['POST'],
                'list'   => ['GET'],
            ]
        ];
        
        $behaviors['responseBehavior'] = ResponseBehavior::class;

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
            'update' => [
                'class' => UpdateAction::class,
                'modelClass' => $this->modelClass,
            ],
            'create' => [
                'class' => CreateAction::class,
                'modelClass' => $this->modelClass,
            ],
        ];
    }
}
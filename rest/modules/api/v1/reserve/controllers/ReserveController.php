<?php

namespace rest\modules\api\v1\reserve\controllers;

use common\models\reserve\ReserveEntity;
use rest\modules\api\v1\reserve\controllers\actions\CreateAction;
use rest\modules\api\v1\reserve\controllers\actions\ListAction;
use rest\modules\api\v1\reserve\controllers\actions\UpdateAction;

/**
 * Class ReserveController
 * @package rest\modules\api\v1\reserve\controllers
 */
class ReserveController extends \yii\rest\Controller
{
    /** @var ReserveEntity $modelClass */
    public $modelClass = ReserveEntity::class;

    /**
     * @return array
     */
    public function behaviors(): array
    {
        $behaviors = parent::behaviors();

        $behaviors['verbs'] = [
            'class'   => \yii\filters\VerbFilter::className(),
            'actions' => [
                'update' => ['PUT'],
                'create' => ['POST'],
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
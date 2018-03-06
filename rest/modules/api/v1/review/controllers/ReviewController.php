<?php

namespace rest\modules\api\v1\review\controllers;

use common\models\review\ReviewEntity;
use rest\behaviors\ResponseBehavior;
use rest\modules\api\v1\review\controllers\actions\DeleteAction;
use rest\modules\api\v1\review\controllers\actions\ListAction;
use rest\modules\api\v1\review\controllers\actions\UpdateAction;
use yii\filters\VerbFilter;
use yii\rest\Controller;
use rest\modules\api\v1\review\controllers\actions\CreateAction;
use yii\filters\auth\HttpBearerAuth;

/**
 * Class ReviewController
 * @package rest\modules\api\v1\review\controllers
 *
 * @mixin ResponseBehavior
 */
class ReviewController extends Controller
{
    /**
     * @var string $modelClass
     */
    public $modelClass = ReviewEntity::class;

    /**
     * @return array
     */
    public function behaviors(): array
    {
        $behaviors = parent::behaviors();

        $behaviors['verbFilter'] = [
            'class'   => VerbFilter::class,
            'actions' => [
                'create' => ['POST'],
                'update' => ['PUT'],
                'list'   => ['GET'],
                'delete' => ['DELETE'],
            ]
        ];

        $behaviors['bearerAuth'] = [
            'class' => HttpBearerAuth::class,
            'only'  => ['create', 'update', 'delete'],
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
            'create' => [
                'class'      => CreateAction::class,
                'modelClass' => $this->modelClass
            ],
            'update' => [
                'class'      => UpdateAction::class,
                'modelClass' => $this->modelClass
            ],
            'list'   => [
                'class'      => ListAction::class,
                'modelClass' => $this->modelClass
            ],
            'delete' => [
                'class'      => DeleteAction::class,
                'modelClass' => $this->modelClass
            ],
        ];
    }
}
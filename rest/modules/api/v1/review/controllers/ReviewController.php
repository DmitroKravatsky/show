<?php

namespace rest\modules\api\v1\review\controllers;

use common\models\review\ReviewEntity;
use rest\modules\api\v1\review\controllers\actions\ListAction;
use rest\modules\api\v1\review\controllers\actions\UpdateAction;
use yii\filters\VerbFilter;
use yii\rest\Controller;
use rest\modules\api\v1\review\controllers\actions\CreateAction;
use yii\filters\auth\HttpBearerAuth;

/**
 * Class ReviewController
 * @package rest\modules\api\v1\review\controllers
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
            'class'   => VerbFilter::className(),
            'actions' => [
                'create' => ['POST'],
                'update' => ['PUT'],
                'list'   => ['GET'],
            ]
        ];

        $behaviors['bearerAuth'] = [
            'class' => HttpBearerAuth::className(),
            'only'  => ['create', 'update'],
        ];

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
            'list' => [
                'class'      => ListAction::className(),
                'modelClass' => $this->modelClass
            ],
        ];
    }
}
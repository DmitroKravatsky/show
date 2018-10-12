<?php

namespace rest\modules\api\v1\bid\controllers;

use common\models\bid\BidEntity;
use rest\modules\api\v1\bid\controllers\actions\{
    ListAction, CreateAction, DeleteAction, DetailAction
};
use yii\filters\auth\HttpBearerAuth;
use yii\rest\Controller;
use yii\filters\VerbFilter;
use common\behaviors\AccessUserStatusBehavior;

/**
 * Class BidController
 * @package rest\modules\api\v1\bid\controllers
 * @mixin AccessUserStatusBehavior
 *
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

        $behaviors['accessUserStatus'] = [
            'class'   => AccessUserStatusBehavior::class,
            'message' => 'Доступ запрещён.'
        ];

        return $behaviors;
    }

    /**
     * @param \yii\base\Action $action
     * @return bool
     */
    public function beforeAction($action): bool
    {
        parent::beforeAction($action);
        $this->checkUserRole();

        return true;
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
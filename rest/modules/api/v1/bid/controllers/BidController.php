<?php

namespace rest\modules\api\v1\bid\controllers;

use common\models\bid\BidEntity;
use rest\behaviors\{ ResponseBehavior, ValidationExceptionFirstMessage };
use rest\modules\api\v1\bid\controllers\actions\{
    ListAction, CreateAction, DeleteAction, UpdateAction, DetailAction
};

/**
 * Class BidController
 * @mixin ResponseBehavior
 * @mixin ValidationExceptionFirstMessage
 * @package rest\modules\api\v1\bid\controllers
 */
class BidController extends \yii\rest\Controller
{
    /** @var BidEntity $modelClass */
    public $modelClass = BidEntity::class;

    /**
     * @return array
     */
    public function behaviors(): array
    {
        $behaviors = parent::behaviors();

        $behaviors['responseBehavior'] = ResponseBehavior::className();

        $behaviors['validationExceptionFirstMessage'] = ValidationExceptionFirstMessage::className();

        $behaviors['verbs'] = [
            'class' => \yii\filters\VerbFilter::className(),
            'actions' => [
                'create' => ['post'],
                'update' => ['put'],
                'delete' => ['delete'],
                'list' => ['get'],
                'detail' => ['get'],
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

        $actions['create'] = [
            'class' => CreateAction::class,
            'modelClass' => $this->modelClass
        ];

        $actions['update'] = [
            'class' => UpdateAction::class,
            'modelClass' => $this->modelClass
        ];

        $actions['delete'] = [
            'class' => DeleteAction::class,
            'modelClass' => $this->modelClass
        ];

        $actions['list'] = [
            'class' => ListAction::class,
            'modelClass' => $this->modelClass
        ];

        $actions['detail'] = [
            'class' => DetailAction::class,
            'modelClass' => $this->modelClass
        ];

        return $actions;
    }
}
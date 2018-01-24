<?php

namespace rest\modules\api\v1\reserve\controllers;

use common\models\reserve\ReserveEntity;
use rest\behaviors\ResponseBehavior;
use rest\behaviors\ValidationExceptionFirstMessage;
use rest\modules\api\v1\reserve\controllers\actions\ListAction;
use rest\modules\api\v1\reserve\controllers\actions\UpdateAction;

/**
 * Class ReserveController
 * @package rest\modules\api\v1\reserve\controllers
 * @mixin ResponseBehavior
 * @mixin ValidationExceptionFirstMessage
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

        $behaviors['responseBehavior'] = ResponseBehavior::className();

        $behaviors['validationExceptionFirstMessage'] = ValidationExceptionFirstMessage::className();

        $behaviors['verbs'] = [
            'class'   => \yii\filters\VerbFilter::className(),
            'actions' => [
                'update' => ['put'],
                'list'   => ['get'],
            ]
        ];

        return $behaviors;
    }

    /**
     * @return array
     */
    public function actions(): array
    {
        $actions = parent::actions();

        $actions['list'] = [
            'class' => ListAction::class,
            'modelClass' => $this->modelClass,
        ];

        $actions['update'] = [
            'class' => UpdateAction::class,
            'modelClass' => $this->modelClass,
        ];

        return $actions;
    }
}
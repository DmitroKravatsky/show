<?php

namespace rest\modules\api\v1\user\controllers;

use common\models\userNotifications\UserNotificationsEntity;
use rest\behaviors\ResponseBehavior;
use rest\modules\api\v1\user\controllers\actions\notifications\DeleteAction;
use rest\modules\api\v1\user\controllers\actions\notifications\ListAction;
use yii\rest\Controller;
use yii\filters\auth\HttpBearerAuth;
use yii\filters\VerbFilter;

/**
 * Class UserNotificationsController
 * @package rest\modules\api\v1\user\controllers
 * 
 * @mixin ResponseBehavior
 */
class UserNotificationsController extends Controller
{
    public $modelClass = UserNotificationsEntity::class;

    /**
     * @return array
     */
    public function behaviors(): array
    {
        $behaviors = parent::behaviors();

        $behaviors['bearerAuth'] = [
            'class' => HttpBearerAuth::className(),
        ];

        $behaviors['verbs'] = [
            'class'   => VerbFilter::className(),
            'actions' => [
                'list'   => ['GET'],
                'delete' => ['DELETE'],
            ]
        ];
        
        $behaviors['responseBehavior'] = ResponseBehavior::className();

        return $behaviors;
    }

    /**
     * @return array
     */
    public function actions(): array
    {

        return [
            'list'   => [
                'class'      => ListAction::className(),
                'modelClass' => $this->modelClass
            ],
            'delete' => [
                'class'      => DeleteAction::className(),
                'modelClass' => $this->modelClass,
            ],
        ];
    }
}
<?php

namespace rest\modules\api\v1\user\controllers;

use common\models\userNotifications\UserNotifications;
use rest\modules\api\v1\user\controllers\actions\notifications\DeleteAction;
use rest\modules\api\v1\user\controllers\actions\notifications\ListAction;
use yii\rest\Controller;
use yii\filters\auth\HttpBearerAuth;
use yii\filters\VerbFilter;
use common\behaviors\AccessUserStatusBehavior;

/**
 * Class UserNotificationsController
 * @package rest\modules\api\v1\user\controllers
 * @mixin AccessUserStatusBehavior
 */
class UserNotificationsController extends Controller
{
    public $modelClass = UserNotifications::class;

    public $serializer = [
        'class' => 'yii\rest\Serializer',
        'collectionEnvelope' => 'items',
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
                'list'   => ['GET'],
                'delete' => ['DELETE'],
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
    public function actions(): array
    {

        return [
            'list'   => [
                'class'      => ListAction::class,
                'modelClass' => $this->modelClass
            ],
            'delete' => [
                'class'      => DeleteAction::class,
                'modelClass' => $this->modelClass,
            ],
        ];
    }
}

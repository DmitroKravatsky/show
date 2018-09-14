<?php

namespace rest\modules\api\v1\user\controllers;

use common\models\userNotifications\UserNotifications;
use rest\modules\api\v1\user\controllers\actions\notifications\DeleteAction;
use rest\modules\api\v1\user\controllers\actions\notifications\ListAction;
use yii\rest\Controller;
use yii\filters\auth\HttpBearerAuth;
use yii\filters\VerbFilter;

/**
 * Class UserNotificationsController
 * @package rest\modules\api\v1\user\controllers
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
        
        return $behaviors;
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

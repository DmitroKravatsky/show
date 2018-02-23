<?php

namespace rest\modules\api\v1\authorization\controllers;

use rest\behaviors\ResponseBehavior;
use rest\modules\api\v1\authorization\controllers\actions\authorization\LoginAction;
use rest\modules\api\v1\authorization\controllers\actions\authorization\LoginGuestAction;
use rest\modules\api\v1\authorization\controllers\actions\authorization\RegisterAction;
use yii\rest\Controller;
use yii\filters\VerbFilter;
use rest\modules\api\v1\authorization\models\RestUserEntity;

/**
 * Class AuthorizationController
 * @package rest\modules\api\v1\authorization\controllers
 *
 * @mixin ResponseBehavior
 */
class AuthorizationController extends Controller
{
    /** @var RestUserEntity */
    public $modelClass = RestUserEntity::class;

    /**
     * @return array
     */
    public function behaviors(): array
    {
        $behaviors = parent::behaviors();

        $behaviors['verbs'] = [
            'class'   => VerbFilter::className(),
            'actions' => [
                'register'    => ['POST'],
                'login'       => ['POST'],
                'login-guest' => ['POST'],
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
            'register' => [
                'class'      => RegisterAction::class,
                'modelClass' => $this->modelClass
            ],
            'login' => [
                'class'      => LoginAction::class,
                'modelClass' => $this->modelClass
            ],
            'login-guest' => [
                'class'      => LoginGuestAction::className(),
                'modelClass' => $this->modelClass
            ],
        ];
    }
}
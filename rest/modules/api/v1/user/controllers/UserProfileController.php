<?php

namespace rest\modules\api\v1\user\controllers;

use common\models\userProfile\UserProfileEntity;
use common\models\userSocial\UserSocial;
use rest\modules\api\v1\authorization\models\RestUserEntity;
use rest\modules\api\v1\user\controllers\actions\profile\{
    BindFbAction, GetProfileAction, UpdateAction, UpdatePasswordAction, BindGmailAction
};
use yii\rest\Controller;
use yii\filters\VerbFilter;
use yii\filters\auth\HttpBearerAuth;
use yii\filters\AccessControl;

/**
 * Class UserProfileController
 * @package rest\modules\api\v1\user\controllers
 *
 */
class UserProfileController extends Controller
{
    /** @var  UserProfileEntity $modelClass */
    public $modelClass = UserProfileEntity::class;

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
                'update'          => ['PUT'],
                'get-profile'     => ['GET'],
                'update-password' => ['PUT'],
                'bind-gmail'      => ['POST'],
                'bind-fb'         => ['POST'],
            ]
        ];

        $behaviors['accessControl'] = [
            'class' => AccessControl::class,
            'rules' => [
                [
                    'allow' => true,
                    'roles' => ['user'],
                ],
            ],
        ];
        
        return $behaviors;
    }

    /**
     * @return array
     */
    public function actions(): array
    {
        $actions = parent::actions();

        $actions['update'] = [
            'class'      => UpdateAction::class,
            'modelClass' => $this->modelClass
        ];

        $actions['get-profile'] = [
            'class'      => GetProfileAction::class,
            'modelClass' => $this->modelClass
        ];

        $actions['update-password'] = [
            'class'      => UpdatePasswordAction::class,
            'modelClass' => RestUserEntity::class
        ];

        $actions['bind-gmail'] = [
            'class'      => BindGmailAction::class,
            'modelClass' => UserSocial::class,
        ];

        $actions['bind-fb'] = [
            'class'      => BindFbAction::class,
            'modelClass' => UserSocial::class,
        ];
        
        return $actions;
    }
}
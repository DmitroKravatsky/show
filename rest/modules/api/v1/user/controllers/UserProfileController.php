<?php

namespace rest\modules\api\v1\user\controllers;

use common\models\userProfile\UserProfileEntity;
use rest\modules\api\v1\user\controllers\actions\profile\{ GetProfileAction, UpdateAction };
use yii\rest\Controller;
use yii\filters\VerbFilter;
use yii\filters\auth\HttpBearerAuth;

/**
 * Class UserProfileController
 * @package rest\modules\api\v1\user\controllers
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
            'class' => HttpBearerAuth::className(),
        ];

        $behaviors['verbs'] = [
            'class'   => VerbFilter::className(),
            'actions' => [
                'update'      => ['put'],
                'get-profile' => ['get'],
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

        $actions['update'] = [
            'class'      => UpdateAction::class,
            'modelClass' => $this->modelClass
        ];

        $actions['get-profile'] = [
            'class'      => GetProfileAction::class,
            'modelClass' => $this->modelClass
        ];
        
        return $actions;
    }
}
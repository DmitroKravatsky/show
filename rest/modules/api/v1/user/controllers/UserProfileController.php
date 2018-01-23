<?php

namespace rest\modules\api\v1\user\controllers;

use common\models\userProfile\UserProfileEntity;
use rest\modules\api\v1\user\actions\profile\{ GetProfileAction, UpdateAction };
use yii\rest\Controller;
use rest\behaviors\{ ResponseBehavior, ValidationExceptionFirstMessage };
use yii\filters\VerbFilter;

/**
 * Class UserProfileController
 * @package rest\modules\api\v1\user\controllers
 * @mixin ValidationExceptionFirstMessage
 * @mixin ResponseBehavior
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

        $behaviors['responseBehavior'] = ResponseBehavior::className();
        $behaviors['validationExceptionFirstMessage'] = ValidationExceptionFirstMessage::className();

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
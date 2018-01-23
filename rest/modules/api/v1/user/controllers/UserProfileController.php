<?php

namespace rest\modules\api\v1\user\controllers;

use common\models\userProfile\UserProfileEntity;
use yii\rest\Controller;
use rest\modules\api\v1\user\actions\profile\UpdateAction;
use rest\behaviors\ResponseBehavior;
use rest\behaviors\ValidationExceptionFirstMessage;
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
                'update' => ['put'],
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
        
        return $actions;
    }
}
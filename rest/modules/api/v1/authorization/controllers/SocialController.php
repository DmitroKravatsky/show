<?php

namespace rest\modules\api\v1\authorization\controllers;

use common\models\user\User;
use rest\modules\api\v1\authorization\controllers\actions\social\{
    FbRegisterAction, GmailLoginAction, GmailRegisterAction, VkLoginAction, VkRegisterAction
};
use yii\filters\VerbFilter;
use rest\behaviors\ResponseBehavior;
use rest\behaviors\ValidationExceptionFirstMessage;

/**
 * Class SocialController
 * @package rest\modules\api\v1\authorization\controllers
 */
class SocialController extends \yii\rest\Controller
{
    /** @var User */
    public $modelClass = User::class;

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
                'vk-register'    => ['post'],
                'vk-login'       => ['post'],
                'gmail-register' => ['post'],
                'gmail-login'    => ['post'],
                'fb-register'    => ['post'],
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

        $actions['vk-register'] = [
            'class'      => VkRegisterAction::class,
            'modelClass' => $this->modelClass
        ];

        $actions['vk-login'] = [
            'class'      => VkLoginAction::class,
            'modelClass' => $this->modelClass
        ];

        $actions['gmail-register'] = [
            'class'      => GmailRegisterAction::class,
            'modelClass' => $this->modelClass
        ];

        $actions['gmail-login'] = [
            'class'      => GmailLoginAction::class,
            'modelClass' => $this->modelClass
        ];

        $actions['fb-register'] = [
            'class'      => FbRegisterAction::class,
            'modelClass' => $this->modelClass
        ];

        return $actions;
    }
}
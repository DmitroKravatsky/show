<?php

namespace rest\modules\api\v1\authorization\controllers;

use rest\modules\api\v1\authorization\controllers\actions\social\{
    FbLoginAction, FbRegisterAction, GmailLoginAction, GmailRegisterAction, VkLoginAction, VkRegisterAction
};
use rest\modules\api\v1\authorization\models\RestUserEntity;
use yii\filters\VerbFilter;
use rest\behaviors\ResponseBehavior;
use yii\rest\Controller;

/**
 * Class SocialController
 * @package rest\modules\api\v1\authorization\controllers
 *
 * @mixin ResponseBehavior
 */
class SocialController extends Controller
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
            'class'   => VerbFilter::class,
            'actions' => [
                'vk-register'    => ['POST'],
                'vk-login'       => ['POST'],
                'gmail-register' => ['POST'],
                'gmail-login'    => ['POST'],
                'fb-register'    => ['POST'],
                'fb-login'       => ['POST'],
            ]
        ];

        $behaviors['responseBehavior'] = ResponseBehavior::class;

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

        $actions['fb-login'] = [
            'class'      => FbLoginAction::class,
            'modelClass' => $this->modelClass
        ];

        return $actions;
    }
}
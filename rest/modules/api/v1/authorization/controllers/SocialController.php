<?php

namespace rest\modules\api\v1\authorization\controllers;

use rest\modules\api\v1\authorization\controllers\actions\social\{
<<<<<<< HEAD
    FbLoginAction, FbRegisterAction, GmailAuthorizeAction, VkLoginAction, VkRegisterAction
=======
    FbAuthorizeAction, GmailLoginAction, GmailRegisterAction, VkLoginAction, VkRegisterAction
>>>>>>> 2a3df51abbd3c454e76ff49a11b52449f82b7de0
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
<<<<<<< HEAD
                'vk-register'         => ['POST'],
                'vk-login'            => ['POST'],
                'gmail-authorize'     => ['POST'],
                'fb-register'         => ['POST'],
                'fb-login'            => ['POST'],
=======
                'vk-register'    => ['POST'],
                'vk-login'       => ['POST'],
                'gmail-register' => ['POST'],
                'gmail-login'    => ['POST'],
                'fb-authorization' => ['POST'],
>>>>>>> 2a3df51abbd3c454e76ff49a11b52449f82b7de0
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

        $actions['gmail-authorize'] = [
            'class'      => GmailAuthorizeAction::class,
            'modelClass' => $this->modelClass
        ];

        $actions['fb-authorize'] = [
            'class'      => FbAuthorizeAction::class,
            'modelClass' => $this->modelClass
        ];

        return $actions;
    }
}
<?php

namespace rest\modules\api\v1\authorization\controllers;

use common\models\oauth\OauthEntity;
use rest\modules\api\v1\authorization\controllers\actions\social\VkRegisterAction;
use yii\filters\VerbFilter;
use rest\behaviors\ResponseBehavior;
use rest\behaviors\ValidationExceptionFirstMessage;

/**
 * Class SocialController
 * @package rest\modules\api\v1\authorization\controllers
 */
class SocialController extends \yii\rest\Controller
{
    /** @var OauthEntity */
    public $modelClass = OauthEntity::class;

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
                'vk-register' => ['post']
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

        return $actions;
    }
}
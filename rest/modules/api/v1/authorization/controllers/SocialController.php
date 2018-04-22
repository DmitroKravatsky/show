<?php

namespace rest\modules\api\v1\authorization\controllers;

use rest\modules\api\v1\authorization\controllers\actions\social\{
    FbAuthorizeAction, GmailAuthorizeAction
};
use rest\modules\api\v1\authorization\models\RestUserEntity;
use yii\filters\VerbFilter;
use yii\rest\Controller;

/**
 * Class SocialController
 * @package rest\modules\api\v1\authorization\controllers
 *
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
                // todo удали пока вообще vk
                'gmail-authorize'   => ['POST'],
                'fb-authorization'  => ['POST'],
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

        $actions['gmail-authorize'] =  [
            'class'      => GmailAuthorizeAction::class,
            'modelClass' => $this->modelClass
        ];

        $actions['fb-authorize']    =  [
            'class'      => FbAuthorizeAction::class,
            'modelClass' => $this->modelClass
        ];

        return $actions;
    }
}
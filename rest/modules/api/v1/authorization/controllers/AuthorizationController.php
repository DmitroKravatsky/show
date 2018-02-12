<?php

namespace rest\modules\api\v1\authorization\controllers;

use rest\modules\api\v1\authorization\controllers\actions\authorization\LoginAction;
use rest\modules\api\v1\authorization\controllers\actions\authorization\RegisterAction;
use yii\rest\Controller;
use yii\filters\VerbFilter;
use rest\modules\api\v1\authorization\models\RestUserEntity;

/**
 * Class AuthorizationController
 * @package rest\modules\api\v1\authorization\controllers
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
                'register' => ['post'],
                'login'    => ['post'],
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

        $actions['register'] = [
            'class'      => RegisterAction::class,
            'modelClass' => $this->modelClass
        ];

        $actions['login'] = [
            'class'      => LoginAction::class,
            'modelClass' => $this->modelClass
        ];

        return $actions;
    }
}
<?php

namespace rest\modules\api\v1\authorization\controllers;

use rest\modules\api\v1\authorization\controllers\actions\base\LoginAction;
use rest\modules\api\v1\authorization\controllers\actions\base\RegisterAction;
use yii\rest\Controller;
use yii\filters\VerbFilter;
use rest\modules\api\v1\authorization\models\RestUserEntity;
use rest\behaviors\ResponseBehavior;

/**
 * Class BaseController
 * @package rest\modules\api\v1\authorization\controllers
 */
class BaseController extends Controller
{
    /** @var RestUserEntity */
    public $modelClass = RestUserEntity::class;

    /**
     * @return array
     */
    public function behaviors(): array
    {
        $behaviors = parent::behaviors();

        $behaviors['responseBehavior'] = ResponseBehavior::className();

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
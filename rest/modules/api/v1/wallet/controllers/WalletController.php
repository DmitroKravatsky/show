<?php

namespace rest\modules\api\v1\wallet\controllers;

use rest\behaviors\ResponseBehavior;
use rest\modules\api\v1\wallet\controllers\actions\DeleteAction;
use rest\modules\api\v1\wallet\controllers\actions\ListAction;
use rest\modules\api\v1\wallet\controllers\actions\UpdateAction;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\filters\auth\HttpBearerAuth;
use common\models\wallet\WalletEntity;
use rest\modules\api\v1\wallet\controllers\actions\CreateAction;

/**
 * Class WalletController
 * @package rest\modules\api\v1\wallet\controllers
 * 
 * @mixin ResponseBehavior
 */
class WalletController extends \yii\rest\Controller
{
    /**
     * @var WalletEntity $modelClass
     */
    public $modelClass = WalletEntity::class;

    /**
     * @return array
     */
    public function behaviors(): array
    {
        $behaviors = parent::behaviors();

        $behaviors['verbFilter'] = [
            'class'   => VerbFilter::className(),
            'actions' => [
                'create' => ['POST'],
                'update' => ['PUT'],
                'list'   => ['GET'],
                'delete' => ['DELETE'],
            ]
        ];

        $behaviors['bearerAuth'] = [
            'class' => HttpBearerAuth::className(),
        ];

        $behaviors['AccessControl'] = [
            'class' => AccessControl::className(),
            'rules' => [
                [
                    'allow' => true,
                    'roles' => ['user'],
                ],
            ],
        ];
        
        $behaviors['responseBehavior'] = ResponseBehavior::className();

        return $behaviors;
    }

    /**
     * @return array
     */
    public function actions(): array
    {
        return [
            'create' => [
                'class'      => CreateAction::class,
                'modelClass' => $this->modelClass
            ],
            'update' => [
                'class'      => UpdateAction::class,
                'modelClass' => $this->modelClass
            ],
            'list'   => [
                'class'      => ListAction::className(),
                'modelClass' => $this->modelClass
            ],
            'delete' => [
                'class'      => DeleteAction::className(),
                'modelClass' => $this->modelClass
            ],
        ];
    }
}
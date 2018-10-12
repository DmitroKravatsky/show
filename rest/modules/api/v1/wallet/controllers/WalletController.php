<?php

namespace rest\modules\api\v1\wallet\controllers;

use rest\modules\api\v1\wallet\controllers\actions\DeleteAction;
use rest\modules\api\v1\wallet\controllers\actions\ListAction;
use rest\modules\api\v1\wallet\controllers\actions\UpdateAction;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\filters\auth\HttpBearerAuth;
use common\models\wallet\WalletEntity;
use rest\modules\api\v1\wallet\controllers\actions\CreateAction;
use yii\rest\Controller;
use common\behaviors\AccessUserStatusBehavior;

/**
 * Class WalletController
 * @package rest\modules\api\v1\wallet\controllers
 * @mixin AccessUserStatusBehavior
 */
class WalletController extends Controller
{
    /**
     * @var WalletEntity $modelClass
     */
    public $modelClass = WalletEntity::class;

    public $serializer = [
        'class' => 'yii\rest\Serializer',
        'collectionEnvelope' => 'items'
    ];

    /**
     * @return array
     */
    public function behaviors(): array
    {
        $behaviors = parent::behaviors();

        $behaviors['verbFilter'] = [
            'class'   => VerbFilter::class,
            'actions' => [
                'create' => ['POST'],
                'update' => ['PUT'],
                'list'   => ['GET'],
                'delete' => ['DELETE'],
            ]
        ];

        $behaviors['bearerAuth'] = [
            'class' => HttpBearerAuth::class,
        ];

        $behaviors['AccessControl'] = [
            'class' => AccessControl::class,
            'rules' => [
                [
                    'allow' => true,
                    'roles' => ['user'],
                ],
            ],
        ];

        $behaviors['accessUserStatus'] = [
            'class'   => AccessUserStatusBehavior::class,
            'message' => 'Доступ запрещён.'
        ];

        return $behaviors;
    }

    /**
     * @param \yii\base\Action $action
     * @return bool
     */
    public function beforeAction($action): bool
    {
        parent::beforeAction($action);
        $this->checkUserRole();

        return true;
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
                'class'      => ListAction::class,
                'modelClass' => $this->modelClass
            ],
            'delete' => [
                'class'      => DeleteAction::class,
                'modelClass' => $this->modelClass
            ],
        ];
    }
}
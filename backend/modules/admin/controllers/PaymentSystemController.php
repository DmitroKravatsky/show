<?php

namespace backend\modules\admin\controllers;

use common\models\paymentSystem\PaymentSystem;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use Yii;
use backend\modules\admin\controllers\actions\paymentSystem\{
    IndexAction, ViewAction, CreateAction, UpdateAction, DeleteAction, ToggleVisibleAction
};

class PaymentSystemController extends Controller
{
    /**
     * @return array
     */
    public function behaviors(): array
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'allow'   => true,
                        'actions' => ['index', 'view', 'toggle-visible', 'update',],
                        'roles'   => ['admin', 'manager',]
                    ],
                    [
                        'allow'   => true,
                        'actions' => ['create', 'delete',],
                        'roles'   => ['admin',]
                    ],
                ],
            ],
        ];
    }

    /**
     * @return array
     */
    public function actions(): array
    {
        return [
            'index' => [
                'class' => IndexAction::class
            ],
            'view' => [
                'class' => ViewAction::class
            ],
            'create' => [
                'class' => CreateAction::class
            ],
            'update' => [
                'class' => UpdateAction::class
            ],
            'delete' => [
                'class' => DeleteAction::class
            ],
            'toggle-visible' => [
                'class' => ToggleVisibleAction::class
            ],
        ];
    }

    /**
     * Finds PaymentSystem model
     * @param integer $id
     * @return PaymentSystem|null
     * @throws NotFoundHttpException
     */
    public function findModel($id)
    {
        if (($reserve = PaymentSystem::findOne($id)) !== null) {
            return $reserve;
        }
        throw new NotFoundHttpException(Yii::t('app', 'Payment System not found'));
    }
}

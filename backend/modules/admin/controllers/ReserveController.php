<?php

namespace backend\modules\admin\controllers;

use backend\modules\admin\controllers\actions\reserve\{
    IndexAction, ToggleVisibleAction, CreateAction, UpdateAction, ViewAction, DeleteAction
};
use common\models\reserve\ReserveEntity;
use yii\web\Controller;
use yii\filters\AccessControl;
use yii\web\NotFoundHttpException;
use Yii;

class ReserveController extends Controller
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
                        'actions' => ['create', 'delete'],
                        'roles'   => ['admin', ]
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
            'toggle-visible' => [
                'class' => ToggleVisibleAction::class
            ],
            'delete' => [
                'class' => DeleteAction::class
            ],
        ];
    }

    /**
     * Finds Reserve model
     * @param integer $id
     * @return ReserveEntity|null
     * @throws NotFoundHttpException
     */
    public function findModel($id)
    {
        if (($reserve = ReserveEntity::findOne($id)) !== null) {
            return $reserve;
        }
        throw new NotFoundHttpException(Yii::t('app', 'Reserve not found'));
    }
}

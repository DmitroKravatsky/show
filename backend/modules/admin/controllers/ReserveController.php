<?php

namespace backend\modules\admin\controllers;

use backend\modules\admin\controllers\actions\reserve\{
    IndexAction,
    UpdateAction,
    ViewAction
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
                        'actions' => ['index', 'view', 'update',],
                        'roles'   => ['admin', 'manager',]
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
            'update' => [
                'class' => UpdateAction::class
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

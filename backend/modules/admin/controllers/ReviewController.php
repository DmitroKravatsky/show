<?php

namespace backend\modules\admin\controllers;

use common\models\review\ReviewEntity;
use yii\web\Controller;
use yii\filters\AccessControl;
use backend\modules\admin\controllers\actions\review\{
    CreateAction, IndexAction, ViewAction, DeleteAction
};
use yii\web\NotFoundHttpException;
use Yii;

class ReviewController extends Controller
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
                        'actions' => ['index', 'view',],
                        'roles'   => ['admin', 'manager']
                    ],
                    [
                        'allow'   => true,
                        'actions' => ['create', 'delete'],
                        'roles'   => ['admin']
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
                'class' => ViewAction::class,
            ],
            'create' => [
                'class' => CreateAction::class,
            ],
            'delete' => [
                'class' => DeleteAction::class,
            ],
        ];
    }

    /**
     * Finds the ReviewEntity model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return ReviewEntity the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function findModel($id)
    {
        if (($review = ReviewEntity::findOne($id)) !== null) {
            return $review;
        }
        throw new NotFoundHttpException(Yii::t('app', 'Review not found'));
    }
}

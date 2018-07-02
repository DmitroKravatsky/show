<?php

namespace backend\modules\admin\controllers;

use backend\modules\admin\controllers\actions\bid\{
    DetailAction, DeleteAction, IndexAction, UpdateBidStatusAction
};
use common\models\bid\BidEntity;
use yii\helpers\Json;
use yii\web\Controller;
use yii\filters\VerbFilter;

/**
 * BidController implements the CRUD actions for BidEntity model.
 */
class BidController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class'      => VerbFilter::class,
                'actions'    => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    public function beforeAction($action)
    {
        if (!\Yii::$app->user->can('admin')) {
            return $this->redirect(\Yii::$app->homeUrl);
        }
        return parent::beforeAction($action);
    }

    public function actions()
    {
        return [
            'index'  => [
                'class' => IndexAction::class
            ],
            'detail' => [
                'class' => DetailAction::class
            ],
            'delete' => [
                'class' => DeleteAction::class
            ],
            'update-bid-status' => [
                'class' => UpdateBidStatusAction::class
            ],
        ];
    }

    /**
     * @return string
     */
    public function actionStatus()
    {
        if (\Yii::$app->request->isAjax) {
            return Json::encode([
                BidEntity::STATUS_ACCEPTED,
                BidEntity::STATUS_PAID,
                BidEntity::STATUS_DONE,
                BidEntity::STATUS_REJECTED,
            ]);
        }
    }
}

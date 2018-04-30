<?php

namespace backend\modules\admin\controllers;

use backend\modules\admin\controllers\actions\bid\DetailAction;
use backend\modules\admin\controllers\actions\bid\IndexAction;
use backend\modules\admin\controllers\actions\bid\UpdateBidStatusAction;
use common\models\bid\BidEntity;
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
                'class' => VerbFilter::className(),
                'actions' => [
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
            'index' => [
                'class' => IndexAction::class
            ],
            'detail' => [
                'class' => DetailAction::class
            ],
            'update-bid-status' => [
                'class' => UpdateBidStatusAction::class
            ],
        ];
    }
}

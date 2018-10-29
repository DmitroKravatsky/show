<?php

namespace backend\modules\admin\controllers;

use backend\modules\admin\controllers\actions\bid\{
    DeleteAction, IndexAction, UpdateBidStatusAction, ViewAction, ToggleProcessedAction
};
use common\models\bid\BidEntity;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\web\NotFoundHttpException;
use yii\filters\AccessControl;

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
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'allow'   => true,
                        'actions' => ['index', 'update-bid-status', 'view', 'toggle-processed',],
                        'roles'   => ['admin', 'manager',]
                    ],
                    [
                        'allow'   => true,
                        'actions' => ['delete',],
                        'roles'   => ['admin',]
                    ],
                ],
            ],
            'verbs' => [
                'class'      => VerbFilter::class,
                'actions'    => [
                    'delete' => ['GET'],
                ],
            ],
        ];
    }

    public function beforeAction($action)
    {
        if (!\Yii::$app->user->can('admin') && !\Yii::$app->user->can('manager')) {
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
            'delete' => [
                'class' => DeleteAction::class
            ],
            'update-bid-status' => [
                'class' => UpdateBidStatusAction::class
            ],
            'view'   => [
                'class' => ViewAction::class
            ],
            'toggle-processed'   => [
                'class' => ToggleProcessedAction::class,
            ],
        ];
    }

    /**
     * Find bid by it id
     * @param $id
     * @return null|BidEntity
     * @throws NotFoundHttpException
     */
    public function findBid($id)
    {
        if (($bid = BidEntity::findOne($id)) !== null) {
            return $bid;
        }
        throw new NotFoundHttpException('Bid not found');
    }
}

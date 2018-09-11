<?php

namespace backend\modules\admin\controllers\actions\bid;

use backend\modules\admin\controllers\BidController;
use common\models\bidHistory\BidHistorySearch;
use yii\base\Action;
use yii\web\NotFoundHttpException;

/**
 * Class ViewAction
 * @package backend\modules\admin\controllers\actions\bid
 * @throws NotFoundHttpException
 */
class ViewAction extends Action
{
    /** @var  BidController */
    public $controller;

    /**
     * View detail bid info
     * @param integer $id
     * @return string
     * @throws NotFoundHttpException
     */
    public function run($id)
    {
        $model = $this->controller->findBid($id);

        $searchModel = new BidHistorySearch();
        $searchModel->bidId = $id;
        $dataProvider = $searchModel->search(\Yii::$app->request->queryParams);

        return $this->controller->render('view', [
            'model'        => $model,
            'searchModel'  => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }
}

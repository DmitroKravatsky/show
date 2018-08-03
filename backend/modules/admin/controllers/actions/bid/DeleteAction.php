<?php

namespace backend\modules\admin\controllers\actions\bid;

use backend\modules\admin\controllers\BidController;
use common\models\bid\BidEntity;
use yii\base\Action;
use yii\web\NotFoundHttpException;

/**
 * Class DeleteAction
 * @package backend\modules\admin\controllers\actions\bid
 */
class DeleteAction extends Action
{
    /** @var  BidController */
    public $controller;
    /**
     * Delete's a bid
     * @param $id integer the id of a bid
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException
     */
    public function run($id)
    {
        if ($this->controller->findBid($id)->delete()) {
            \Yii::$app->session->setFlash('delete-success', 'bid was successfully deleted');
            return $this->controller->redirect('/admin/bids');
        }
        \Yii::$app->session->setFlash('delete-fail', 'Something wrong, please try again later');
        return false;
    }

}
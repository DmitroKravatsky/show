<?php

namespace backend\modules\admin\controllers\actions\bid;

use backend\modules\admin\controllers\BidController;
use Yii;
use yii\base\Action;
use yii\web\NotFoundHttpException;

/**
 * Class ToggleProcessedAction
 * @package backend\modules\admin\controllers\actions\bid
 */
class ToggleProcessedAction extends Action
{
    /** @var  BidController */
    public $controller;

    /**
     * @param $id
     * @return \yii\web\Response
     * @throws NotFoundHttpException
     */
    public function run($id)
    {
        $bid = $this->controller->findBid($id);
        $bid->processed_by = Yii::$app->user->id;

        if ($bid->toggleProcessed()) {
            Yii::$app->session->setFlash('delete-success', Yii::t('app', 'Bid successfully updated.'));
        } else {
            Yii::$app->session->setFlash('delete-success', Yii::t('app', 'Something wrong, please try again later.'));
        }

        return $this->controller->redirect(['bid/index']);
    }
}

<?php

namespace backend\modules\admin\controllers\actions\bid;

use common\models\bid\BidEntity;
use yii\base\Action;

/**
 * Class DetailAction
 * @package backend\modules\admin\controllers\actions\bid
 */
class DetailAction extends Action
{
    /**
     * Show all information about bid
     * @param $id integer the id of a bid
     * @return string|\yii\web\Response
     */
    public function run($id)
    {
        $bid = BidEntity::findOne(['id' => $id]);
        if (\Yii::$app->request->post()) {
            return $this->controller->redirect('@backend/admin/bid/update-bid-status', ['id' => $id]);
        }
        return $this->controller->render('detail', ['modelBid' => $bid]);
    }

}
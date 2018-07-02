<?php

namespace backend\modules\admin\controllers\actions\bid;

use common\models\bid\BidEntity;
use yii\base\Action;

/**
 * Class DeleteAction
 * @package backend\modules\admin\controllers\actions\bid
 */
class DeleteAction extends Action
{
    /**
     * Delete's a bid
     * @param $id integer the id of a bid
     * @return string|\yii\web\Response
     */
    public function run($id)
    {
        $bid = BidEntity::findOne(['id' => $id]);
        if ($bid && $bid->delete()) {
            return true;
        }
        return false;
    }

}
<?php

namespace rest\modules\api\v1\bid\controllers\actions;

use common\models\bid\BidEntity;
use yii\web\ServerErrorHttpException;
use Yii;

/**
 * Class DeleteAction
 * @package rest\modules\api\v1\bid\controllers\actions
 */
class DeleteAction extends \yii\rest\Action
{
    /**
     * @param $id
     * @throws ServerErrorHttpException
     */
    public function run($id)
    {
        /** @var BidEntity $bid */
        $bid = new BidEntity();
        return $bid->deleteBid($id);
    }
}
<?php

namespace rest\modules\api\v1\bid\controllers\actions;

use common\models\bid\BidEntity;
use Yii;
use yii\web\ServerErrorHttpException;

/**
 * Class UpdateAction
 * @package rest\modules\api\v1\bid\controllers\actions
 */
class UpdateAction extends \yii\rest\Action
{
    /**
     * @param $id
     * @return array
     * @throws ServerErrorHttpException
     * @throws \yii\web\NotFoundHttpException
     * @throws \yii\web\UnprocessableEntityHttpException
     */
    public function run($id)
    {
        $bid = new BidEntity();
        return $bid->updateBid($id);
    }
}
<?php

namespace rest\modules\api\v1\bid\controllers\actions;

use common\models\bid\BidEntity;
use yii\web\ServerErrorHttpException;
use Yii;

/**
 * Class CreateAction
 * @package rest\modules\api\v1\comment\controllers\actions
 */
class CreateAction extends \yii\rest\Action
{
    /**
     * @return mixed
     * @throws ServerErrorHttpException
     */
    public function run()
    {
        /** @var BidEntity $bid */
        $bid = new $this->modelClass;
        return $bid->createBid();
    }
}
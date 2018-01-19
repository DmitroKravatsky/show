<?php

namespace rest\modules\api\v1\bid\controllers\actions;

use common\models\bid\BidEntity;

/**
 * Class ListAction
 * @package rest\modules\api\v1\bid\controllers\actions
 */
class ListAction extends \yii\rest\Action
{
    /**
     * @return \yii\data\ArrayDataProvider
     */
    public function run()
    {
        return BidEntity::getBids(\Yii::$app->request->queryParams);
    }
}
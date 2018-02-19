<?php

namespace rest\modules\api\v1\review\controllers\actions;

use common\models\review\ReviewEntity;
use yii\rest\Action;

/**
 * Class ListAction
 * @package rest\modules\api\v1\review\controllers\actions
 */
class ListAction extends Action
{
    /**
     * Returns reviews list
     * @return \yii\data\ArrayDataProvider
     */
    public function run()
    {
        $reviewModel = new ReviewEntity();
        return $reviewModel->listReviews(\Yii::$app->requestedParams);
    }
}
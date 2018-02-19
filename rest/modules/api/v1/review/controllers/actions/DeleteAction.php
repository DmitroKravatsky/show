<?php

namespace rest\modules\api\v1\review\controllers\actions;

use common\models\review\ReviewEntity;
use yii\rest\Action;

/**
 * Class DeleteAction
 * @package rest\modules\api\v1\review\controllers\actions
 */
class DeleteAction extends Action
{
    /**
     * @param $id
     * @return mixed
     * @throws \yii\web\ServerErrorHttpException
     */
    public function run($id)
    {
        $reviewModel = new ReviewEntity();
        return $reviewModel->deleteReview($id);
    }
}
<?php

namespace rest\modules\api\v1\review\controllers\actions;

use common\models\review\ReviewEntity;
use yii\rest\Action;

/**
 * Class UpdateAction
 * @package rest\modules\api\v1\review\controllers\actions
 */
class UpdateAction extends Action
{
    /**
     * @param $id
     * @return mixed
     * @throws \yii\web\NotFoundHttpException
     */
    public function run($id)
    {
        /** @var ReviewEntity $reviewModel */
        $reviewModel = new $this->modelClass;
        return $reviewModel->updateReview($id, \Yii::$app->request->bodyParams);
    }
}
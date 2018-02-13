<?php

namespace rest\modules\api\v1\review\controllers\actions;

use common\models\review\ReviewEntity;
use yii\rest\Action;

/**
 * Class CreateAction
 * @package rest\modules\api\v1\review\controllers\actions
 */
class CreateAction extends Action
{
    /**
     * @return mixed
     */
    public function run()
    {
        /** @var ReviewEntity $reviewModel */
        $reviewModel = new $this->modelClass;
        return $reviewModel->create(\Yii::$app->request->bodyParams);
    }
}
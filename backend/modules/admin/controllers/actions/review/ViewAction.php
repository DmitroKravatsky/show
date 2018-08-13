<?php

namespace backend\modules\admin\controllers\actions\review;

use backend\modules\admin\controllers\ReviewController;
use yii\base\Action;

class ViewAction extends Action
{
    /**
     * @var ReviewController
     */
    public $controller;

    /**
     * @param $id
     * @return string
     * @throws \yii\web\NotFoundHttpException
     */
    public function run($id): string
    {
        $review = $this->controller->findModel($id);

        return $this->controller->render('view', [
            'review' => $review,
        ]);
    }
}

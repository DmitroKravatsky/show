<?php

namespace backend\modules\admin\controllers\actions\review;

use Yii;
use backend\modules\admin\controllers\ReviewController;
use yii\base\Action;
use yii\helpers\Url;

class ToggleVisibleAction extends Action
{
    /** @var  ReviewController */
    public $controller;

    public function run($id)
    {
        $review = $this->controller->findModel($id);
        if ($review->toggleVisible()) {
            Yii::$app->session->setFlash('success', Yii::t('app', 'Review successfully updated.'));
        } else {
            Yii::$app->session->setFlash('error', Yii::t('app', 'Something wrong, please try again later.'));
        }

        return $this->controller->redirect(Url::to(Yii::$app->request->referrer));
    }
}

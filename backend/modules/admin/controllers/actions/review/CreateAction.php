<?php

namespace backend\modules\admin\controllers\actions\review;

use common\models\review\ReviewEntity;
use yii\base\Action;
use Yii;

class CreateAction extends Action
{
    /**
     * Creates new review
     * @return string
     */
    public function run()
    {
        $review = new ReviewEntity();
        $params = Yii::$app->request->post();
        if ($params) {
            $params['ReviewEntity']['terms_condition'] = 1;
            if ($review->load($params) && $review->save()) {
                \Yii::$app->session->setFlash('success', Yii::t('app', 'Review was successfully created.'));
                return $this->controller->redirect(Yii::$app->request->referrer);
            }
            \Yii::$app->session->setFlash('fail', Yii::t('app', 'Something wrong, please try again later.'));
        }
        return $this->controller->redirect(Yii::$app->request->referrer);
    }
}

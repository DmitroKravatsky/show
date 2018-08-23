<?php

namespace backend\modules\admin\controllers\actions\reserve;

use backend\modules\admin\controllers\ReserveController;
use yii\base\Action;

class ViewAction extends Action
{
    /** @var ReserveController */
    public $controller;

    /**
     * @param integer $id
     * @return string
     * @throws \yii\web\NotFoundHttpException
     */
    public function run($id)
    {
        $reserve = $this->controller->findModel($id);
        return $this->controller->render('view', [
            'reserve' => $reserve,
        ]);
    }
}

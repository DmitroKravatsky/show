<?php

namespace backend\modules\admin\controllers\actions\manager;

use backend\modules\admin\controllers\ManagerController;
use yii\base\Action;

class ViewAction extends Action
{
    /** @var ManagerController */
    public $controller;

    /**
     * Displays a single UserBackend model.
     *
     * @param $id
     * @return string
     * @throws \yii\web\NotFoundHttpException
     */
    public function run($id): string
    {
        $manager = $this->controller->findModel($id);

        return $this->controller->render('view', [
            'manager' => $manager,
        ]);
    }
}

<?php

namespace backend\modules\admin\controllers\actions\manager;

use backend\models\BackendUser;
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
        // For updating password from manager info view
        $manager->setScenario(BackendUser::SCENARIO_UPDATE_PASSWORD_BY_ADMIN);

        return $this->controller->render('view', [
            'manager' => $manager,
        ]);
    }
}

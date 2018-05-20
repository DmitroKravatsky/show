<?php

namespace backend\modules\admin\controllers\actions\admin;

use yii\base\Action;

/**
 * Class DeleteManagerAction
 * @package backend\modules\admin\controllers\actions\admin
 */
class DeleteManagerAction extends Action
{
    /**
     * Delete manager
     * @param $user_id integer Manager id that will be deleted
     * @return string
     */
    public function run($user_id)
    {
        \Yii::$app->authManager->revoke(\Yii::$app->authManager->getRole('manager'), $user_id);
        return $this->controller->redirect('managers-list');

    }
}
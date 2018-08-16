<?php

namespace backend\modules\authorization\controllers\actions\authorization;

use yii\base\Action;

class LogoutAction extends Action
{
    public function run()
    {
        \Yii::$app->user->logout();
        return $this->controller->goHome();
    }
}
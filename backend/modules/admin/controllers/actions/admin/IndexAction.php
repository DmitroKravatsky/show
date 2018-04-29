<?php

namespace backend\modules\admin\controllers\actions\admin;


use yii\base\Action;

class IndexAction extends Action
{
    public $view = '@backend/modules/admin/views/admin/index';

    public function run()
    {
        return $this->controller->render($this->view);
    }
}
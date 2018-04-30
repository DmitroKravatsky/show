<?php

namespace backend\modules\admin\controllers\actions;


use yii\base\Action;

class IndexAction extends Action
{
    public $view = '@backend/modules/admin/views/admin/index';

    public function run()
    {
//        var_dump(12); exit;
        return $this->controller->render($this->view);
    }
}
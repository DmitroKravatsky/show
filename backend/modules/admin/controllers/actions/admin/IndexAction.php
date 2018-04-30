<?php

namespace backend\modules\admin\controllers\actions\admin;

use yii\base\Action;

/**
 * Class IndexAction
 * @package backend\modules\admin\controllers\actions\admin
 */
class IndexAction extends Action
{
    public $view = '@backend/modules/admin/views/admin/index';

    /**
     * Renders an admin panel
     * @return string
     */
    public function run()
    {
        return $this->controller->render($this->view);
    }
}
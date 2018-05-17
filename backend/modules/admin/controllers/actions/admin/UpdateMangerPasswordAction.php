<?php

namespace backend\modules\admin\controllers\actions\admin;

use backend\modules\authorization\models\RegistrationForm;
use common\models\user\User;
use yii\base\Action;

/**
 * Class UpdateMangerPasswordAction
 * @package backend\modules\admin\controllers\actions\admin
 */
class UpdateMangerPasswordAction extends Action
{
    public $view = '@backend/modules/admin/views/admin/update-manager-password';

    /**
     * Renders an admin panel
     * @return string
     */
    public function run($id)
    {
        $user = User::findOne($id);
        $modelRegistration = new RegistrationForm();


        return $this->controller->renderAjax($this->view, [
            'modelRegistration' => $modelRegistration
        ]);
    }
}
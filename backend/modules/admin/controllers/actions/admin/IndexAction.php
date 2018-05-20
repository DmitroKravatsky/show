<?php

namespace backend\modules\admin\controllers\actions\admin;

use backend\modules\authorization\models\RegistrationForm;
use common\models\user\User;
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
    public function run($inviteCode = null)
    {
        if ($inviteCode) {
            $userData = User::find()->select(['email'])->where(['user.invite_code' => $inviteCode])->one();

            $passwordUpdateModel = new RegistrationForm();
            $passwordUpdateModel->setAttributes($userData);
            return $this->controller->render($this->view, ['passwordUpdateModel' => $passwordUpdateModel]);
        }
        return $this->controller->render($this->view);
    }
}
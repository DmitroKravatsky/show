<?php

namespace backend\modules\admin\controllers\actions\admin;

use common\models\user\User;
use yii\base\Action;

/**
 * Class DeleteManagerAction
 * @package backend\modules\admin\controllers\actions\admin
 */
class ReInviteManagerAction extends Action
{
    /**
     * Delete manager
     * @param $user_id integer Manager id that will be deleted
     * @return string
     */
    public function run($user_id)
    {
        $userModel = User::findOne(['id' => $user_id]);
        $newPassword = \Yii::$app->security->generateRandomString(10);
        $userModel->password = \Yii::$app->security->generatePasswordHash($newPassword);
        $userModel->invite_code = \Yii::$app->security->generateRandomString(32);
        $userModel->invite_code_status = "ACTIVE";

        if (\Yii::$app->request->isAjax) {
            if ($userModel->save(false)) {
                \Yii::$app->sendMail->run(
                    'sendLoginLink-html.php',
                    [
                        'email' => $userModel->email,
                        'phone_number' => $userModel->phone_number,
                        'loginLink' => $_SERVER['HTTP_HOST'] . '/admin/login?invite_code=' . $userModel->invite_code,
                        'password' => $newPassword
                    ],
                    \Yii::$app->params['supportEmail'], $userModel->email, 'ConfirmRegistration'
                );
                return true;
            }
        }
        return $this->controller->redirect('managers-list');

    }
}
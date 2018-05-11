<?php

namespace backend\modules\admin\controllers\actions\admin;

use backend\modules\authorization\models\RegistrationForm;
use common\models\user\User;
use common\models\userProfile\UserProfileEntity;
use yii\base\Action;

/**
 * Class IndexAction
 * @package backend\modules\admin\controllers\actions\admin
 */
class InviteManagerAction extends Action
{
    public $view = '@backend/modules/admin/views/admin/invite-manager';

    /**
     * Renders an admin panel
     * @return string
     */
    public function run()
    {
        $modelRegistration = new RegistrationForm();
        if (\Yii::$app->request->post()) {
            $modelRegistration->load(\Yii::$app->request->post());
            if ($modelRegistration->validate()) {
                $userModel   = new User();
                $userProfile = new UserProfileEntity();

                $userModel->setAttributes([
                    'email'       => $modelRegistration->email,
                    'password'    => $modelRegistration->password,
                    'invite_code' => \Yii::$app->security->generateRandomString(32),
                    'auth_key'    => \Yii::$app->security->generateRandomString(),
                ], false);

                $userProfile->setAttributes([
                    'name'      => $modelRegistration->name,
                    'last_name' => $modelRegistration->name,
                ], false);
                $userRole = \Yii::$app->authManager->getRole($modelRegistration['role']);

                $transaction = \Yii::$app->db->beginTransaction();

                if ($userModel->save()
                    && $userProfile->save()
                    && \Yii::$app->authManager->assign($userRole, $userModel->getId())
                ) {
                    $transaction->commit();

                    \Yii::$app->sendMail->run(
                        'sendLoginLink-html.php',
                        [
                            'email' => $modelRegistration->email,
                            'loginLink' =>$_SERVER['HTTP_HOST'] . '/admin/login?invite_code=' . $userModel->invite_code
                        ],
                        \Yii::$app->params['supportEmail'], $modelRegistration->email, 'ConfirmRegistration'
                    );
                    return $this->controller->redirect('managers-list');
                }

            }
            return $this->controller->render($this->view, [
                'modelRegistration' => $modelRegistration
            ]);
        }

        return $this->controller->render($this->view, [
            'modelRegistration' => $modelRegistration
        ]);
    }
}
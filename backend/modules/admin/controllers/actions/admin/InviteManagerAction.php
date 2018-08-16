<?php

namespace backend\modules\admin\controllers\actions\admin;

use backend\modules\authorization\models\RegistrationForm;
use common\models\user\User;
use common\models\userProfile\UserProfileEntity;
use yii\base\Action;
use Yii;
use yii\widgets\ActiveForm;
use yii\web\Response;

/**
 * Class InviteManagerAction
 * @package backend\modules\admin\controllers\actions\admin
 */
class InviteManagerAction extends Action
{
    public $view = '@backend/modules/admin/views/admin/invite-manager';

    /**
     * Renders an admin panel
     *
     * @return array|string|Response
     * @throws \yii\base\Exception
     * @throws \yii\db\Exception
     */
    public function run()
    {
        $modelRegistration = new RegistrationForm();

        if (Yii::$app->request->isAjax && $modelRegistration->load(Yii::$app->request->post())) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($modelRegistration);
        }

        if (\Yii::$app->request->post()) {
            $modelRegistration->load(\Yii::$app->request->post());
            if ($modelRegistration->validate()) {
                $userModel   = new User();
                $userProfile = new UserProfileEntity();
                $userModel->setAttributes([
                    'email'         => $modelRegistration->email,
                    'phone_number'  => $modelRegistration->phone_number,
                    'invite_code'   => \Yii::$app->security->generateRandomString(32),
                    'auth_key'      => \Yii::$app->security->generateRandomString(),
                    'password'      => \Yii::$app->security->generatePasswordHash($modelRegistration->password)
                ], false);

                $userRole = \Yii::$app->authManager->getRole(User::ROLE_MANAGER);

                $transaction = \Yii::$app->db->beginTransaction();

                if ($userModel->save()) {

                    $userProfile->setAttributes([
                        'user_id'   => $userModel->id,
                        'name'      => $modelRegistration->name,
                        'last_name' => $modelRegistration->last_name,
                    ], false);
                    if ($userProfile->save()
                        && \Yii::$app->authManager->assign($userRole, $userModel->getId())
                    ) {
                        $transaction->commit();

                        \Yii::$app->sendMail->run(
                            'sendLoginLink-html.php',
                            [
                                'email' => $modelRegistration->email,
                                'loginLink' =>  Yii::$app->urlManager->createAbsoluteUrl(['/admin/login', 'invite_code' => $userModel->invite_code]),
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

        return $this->controller->render($this->view, [
            'modelRegistration' => $modelRegistration
        ]);
    }
}
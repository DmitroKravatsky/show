<?php

namespace backend\modules\admin\controllers\actions\manager;

use backend\modules\authorization\models\RegistrationForm;
use common\models\user\User;
use common\models\userProfile\UserProfileEntity;
use yii\base\Action;
use Yii;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use yii\web\Response;

/**
 * Class InviteAction
 * @package backend\modules\admin\controllers\actions\manager
 */
class InviteAction extends Action
{
    /**
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

        if (Yii::$app->request->post() && $modelRegistration->load(Yii::$app->request->post())) {
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
                                'loginLink' =>  Yii::$app->urlManager->createAbsoluteUrl(
                                    [
                                        'login',
                                        'invite_code' => $userModel->invite_code
                                    ]
                                ),
                            ],
                            \Yii::$app->params['supportEmail'], $modelRegistration->email, 'ConfirmRegistration'
                        );
                        Yii::$app->session->setFlash('success', Yii::t('app', 'Manager successfully created.'));
                        return $this->controller->redirect(Url::to(['/manager/index']));
                    }
                }
                return $this->controller->render('invite', [
                    'modelRegistration' => $modelRegistration
                ]);
            }
            return $this->controller->render('invite', [
                'modelRegistration' => $modelRegistration
            ]);
        }

        return $this->controller->render('invite', [
            'modelRegistration' => $modelRegistration
        ]);
    }
}

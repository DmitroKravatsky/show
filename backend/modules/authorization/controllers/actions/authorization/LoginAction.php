<?php

namespace backend\modules\authorization\controllers\actions\authorization;

use backend\modules\authorization\models\LoginForm;
use yii\base\Action;
use Yii;

class LoginAction extends Action
{
    public $view = '@backend/modules/authorization/views/authorization/login';
    public $layout = '@backend/views/layouts/login';

    public function run()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->controller->redirect('/admin/index');
        }

        $this->controller->layout = $this->layout;

        $modelLogin = new LoginForm();

        if ($invitedCode = Yii::$app->request->get('invite_code')) {
            $isLogin = $modelLogin->loginByInvite(Yii::$app->request->get('invite_code'));
            if (!$isLogin) {
                Yii::$app->user->logout();
                Yii::$app->session->setFlash('error', Yii::t('app', 'Invitation link expired.'));
                return $this->controller->goHome();
            }

            return $this->controller->redirect(['/index', 'inviteCode' => $invitedCode]);
        }

        if ($modelLogin->load(\Yii::$app->request->post()) && $modelLogin->login()) {
            if (Yii::$app->user->can('admin') || Yii::$app->user->can('manager')) {
                return $this->controller->redirect(['/index']);
            } else {
                Yii::$app->getSession()->setFlash('error', "You haven't permission to enter to protected area. Please check your credentials.");
                return $this->controller->redirect(Yii::$app->homeUrl);
            }
        } else {
            $modelLogin->addError('password', \Yii::t('app', 'Incorrect email or password.'));
        }

        return $this->controller->render($this->view, [
            'modelLogin' => $modelLogin,
        ]);
    }
}

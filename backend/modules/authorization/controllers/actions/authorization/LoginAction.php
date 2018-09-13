<?php

namespace backend\modules\authorization\controllers\actions\authorization;

use backend\models\BackendUser;
use backend\modules\authorization\models\LoginForm;
use yii\{ base\Action, widgets\ActiveForm, web\Response };
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

        if (Yii::$app->request->isAjax && $modelLogin->load(Yii::$app->request->post())) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($modelLogin);
        }

        if ($modelLogin->load(Yii::$app->request->post()) && $modelLogin->login()) {
            if (Yii::$app->user->can(BackendUser::ROLE_ADMIN) || Yii::$app->user->can(BackendUser::ROLE_MANAGER)) {
                return $this->controller->redirect(['/index']);
            } else {
                Yii::$app->getSession()->setFlash('error', "You haven't permission to enter to protected area. Please check your credentials.");
                return $this->controller->redirect(Yii::$app->homeUrl);
            }
        }

        return $this->controller->render($this->view, [
            'modelLogin' => $modelLogin,
        ]);
    }

    public function beforeRun()
    {
        Yii::$app->language = Yii::$app->session->get('language', Yii::$app->language);
        return true;
    }
}

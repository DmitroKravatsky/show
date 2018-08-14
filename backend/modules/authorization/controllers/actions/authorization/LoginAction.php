<?php

namespace backend\modules\authorization\controllers\actions\authorization;

use backend\modules\authorization\models\LoginForm;
use backend\modules\authorization\models\RegistrationForm;
use yii\base\Action;
use yii\web\NotFoundHttpException;

class LoginAction extends Action
{
    public $view = '@backend/modules/authorization/views/authorization/login';
    public $layout = '@backend/views/layouts/login';
    public $errorLayout = '@backend/views/layouts/error';

    public function run()
    {
        $this->controller->layout = $this->layout;

        $modelLogin = new LoginForm();

        if ($invitedCode = \Yii::$app->request->get('invite_code')) {
            $result = $modelLogin->loginByInvite(\Yii::$app->request->get('invite_code'));

            if (!$result) {
                return $this->controller->redirect(\Yii::$app->homeUrl);
            }

            return $this->controller->redirect(['/index', 'inviteCode' => $invitedCode]);
        }

        if (!\Yii::$app->user->isGuest && \Yii::$app->user->can('admin')) {
            return $this->controller->redirect('/admin/index');
        }

        if ($modelLogin->load(\Yii::$app->request->post()) && $modelLogin->login()) {
            if (\Yii::$app->user->can('admin') || \Yii::$app->user->can('manager')) {
                return $this->controller->redirect(['/index']);
            } else {
                \Yii::$app->getSession()->setFlash('Enter_failed', "You haven't permission to enter to protected area. Please check your credentials. ");
                return $this->controller->redirect(\Yii::$app->homeUrl);
            }
        }

        return $this->controller->render($this->view, [
            'modelLogin' => $modelLogin,
        ]);
    }
}

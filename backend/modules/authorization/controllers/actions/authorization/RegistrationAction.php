<?php

namespace backend\modules\authorization\controllers\actions\authorization;

use backend\modules\authorization\models\LoginForm;
use backend\modules\authorization\models\RegistrationForm;
use yii\base\Action;

class RegistrationAction extends Action
{
    public $viewRegistration = '@backend/modules/authorization/views/authorization/login';
    public $viewSuccess = '@backend/modules/authorization/views/authorization/success';
    /**
     * @return string
     */
    public static function getActionName(): string
    {
        return 'registration';
    }
    /**
     * Action registration implements registration for "shop/user"-role users.
     * Validate input data and in success case send mail.
     *
     * @return string
     */
    public function run(): string
    {
        /** @var  $model RegistrationForm.php */
        $modelRegistration = new RegistrationForm();
        /** @var  $modelLogin LoginForm.php */
        $modelLogin = new LoginForm();

        $postData = \Yii::$app->request->post();
        var_dump($postData); exit;
        if (isset($postData['RegistrationForm']['termsConditions'])) {
            $postData['RegistrationForm']['termsConditions'] = 1;
        }

        if ($modelRegistration->load($postData) && $user = $modelRegistration->registration()) {
            \Yii::$app->sendMail->run(
                'sendLoginLink-html.php',
                ['email' => $modelRegistration->email, 'loginLink' => $this->verification_code],
                \Yii::$app->params['supportEmail'], $modelRegistration->email, 'ConfirmRegistration'
            );
            return $this->controller->render($this->viewSuccess);
        } else {
            return $this->controller->render($this->viewRegistration,
                ['modelLogin' => $modelLogin, 'modelRegistration' => $modelRegistration]);
        }
    }
}
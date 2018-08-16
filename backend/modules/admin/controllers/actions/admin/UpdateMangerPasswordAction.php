<?php

namespace backend\modules\admin\controllers\actions\admin;

use backend\modules\authorization\models\RegistrationForm;
use common\models\user\User;
use yii\base\Action;
use Yii;

/**
 * Class UpdateMangerPasswordAction
 * @package backend\modules\admin\controllers\actions\admin
 */
class UpdateMangerPasswordAction extends Action
{
    public $view = '@backend/modules/admin/views/admin/update-manager-password';

    /**
     * Renders an admin panel
     * @return string|\yii\web\Response
     * @throws \yii\base\Exception
     */
    public function run()
    {
        $modelRegistration = new RegistrationForm();

        if (\Yii::$app->request->post()) {
            $modelRegistration->setScenario($modelRegistration::SCENARIO_PASSWORD_CREATE);
            if ($modelRegistration->load(\Yii::$app->request->post()) && $modelRegistration->validate()) {
                $user = User::findOne(Yii::$app->user->id);
                $user->invite_code = null;
                $user->password = Yii::$app->security->generatePasswordHash($modelRegistration->password);
                if ($user->save(false)) {
                    Yii::$app->user->logout();
                    Yii::$app->session->setFlash('success', Yii::t('app', 'Password updated successfully.'));
                    return $this->controller->goHome();
                }
            }
            return $this->controller->render($this->view, [
                'modelRegistration' => $modelRegistration
            ]);
        }
    }
}
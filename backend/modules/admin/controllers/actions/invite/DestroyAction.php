<?php

namespace backend\modules\admin\controllers\actions\invite;

use backend\models\BackendUser;
use yii\base\Action;
use Yii;

class DestroyAction extends Action
{
    /**
     * @param string $inviteCode
     * @return bool|\yii\web\Response
     */
    public function run($inviteCode)
    {
        $user = BackendUser::findByInviteCode($inviteCode);
        if ($user == null) {
            return false;
        }

        $user->invite_code = null;
        $user->invite_code_status = BackendUser::STATUS_INVITE_INACTIVE;
        $user->save(false, ['invite_code', 'invite_code_status']);

        Yii::$app->user->logout();
        Yii::$app->session->setFlash('error', Yii::t('app', 'Invitation link expired.'));

        return $this->controller->goHome();
    }
}

<?php

namespace backend\modules\admin\controllers\actions\profile;

use backend\models\BackendUser;
use yii\base\Action;
use yii\web\NotFoundHttpException;
use Yii;

class VerifyAction extends Action
{
    /**
     * @param int $token
     * @return \yii\web\Response
     * @throws NotFoundHttpException
     */
    public function run($token)
    {
        $user = BackendUser::findByVerificationToken($token);
        if ($user === null) {
            throw new NotFoundHttpException(Yii::t('app', 'User is not found'));
        }

        $user->verification_token = null;
        $user->email = $user->new_email;
        $user->new_email = null;

        $attributes = ['email' , 'verification_token', 'new_email',];

        $user->validate($attributes);
        $user->save(false, $attributes);
        Yii::$app->session->setFlash('success', Yii::t('app', 'Email successfully updated.'));

        return $this->controller->redirect('index');
    }
}

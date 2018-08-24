<?php

namespace backend\modules\admin\controllers\actions\profile;

use backend\models\BackendUser;
use common\models\user\User;
use common\models\userProfile\UserProfileEntity;
use yii\base\Action;
use Yii;
use yii\helpers\VarDumper;
use yii\web\ErrorHandler;
use yii\widgets\ActiveForm;
use yii\web\Response;

class UpdateAction extends Action
{
    /**
     * @return array|string|Response
     * @throws \yii\db\Exception
     */
    public function run()
    {
        $user = BackendUser::findOne(Yii::$app->user->id);
        $user->scenario = BackendUser::SCENARIO_UPDATE_PASSWORD;

        /** @var UserProfileEntity $profile */
        $profile = $user->profile;

        $postData = Yii::$app->request->post();

        if (Yii::$app->request->isAjax && $user->load($postData)) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($user);
        }

        $transaction = Yii::$app->db->beginTransaction();
        try {
            $profileAttributes = ['name', 'last_name'];
            if ($user->load($postData) && $profile->load($postData)) {
                $newEmail = $user->email;
                if ($newEmail && $newEmail != $user->getOldAttribute('email') && $profile->validate($profileAttributes)) {
                    $token = Yii::$app->security->generateRandomString();
                    $user->verification_token = $token;
                    $user->new_email = $newEmail;
                    $attributes = ['verification_token', 'new_email'];
                    $user->validate($attributes);
                    $user->save(false, $attributes);
                    $profile->save(false);

                    $transaction->commit();

                    Yii::$app->sendMail->sendMailToUser($user);

                    Yii::$app->session->setFlash('success', Yii::t('app', 'A link has been sent to your mail. To pass the verification procedure, check your mail.'));
                    return $this->controller->redirect(['index']);
                }

                if ($profile->save(true, $profileAttributes)) {
                    $transaction->commit();
                    Yii::$app->session->setFlash('success', Yii::t('app', 'Profile successfully updated.'));
                    return $this->controller->redirect(['index']);
                }
            }
        } catch (\Exception $e) {
            ErrorHandler::convertExceptionToString($e);
            Yii::$app->session->setFlash('error', Yii::t('app', 'Something wrong, please try again later.'));
            $transaction->rollBack();
        }

        return $this->controller->render('index', [
            'activeTab' => 'general',
            'user' => $user,
            'profile' => $profile,
        ]);
    }
}

<?php

namespace backend\modules\admin\controllers\actions\manager;

use common\models\user\User;
use yii\base\Action;
use Yii;
use yii\helpers\Url;
use yii\web\ErrorHandler;
use yii\web\Response;

/**
 * Class ReInviteAction
 * @package backend\modules\admin\controllers\actions\admin
 */
class ReInviteAction extends Action
{
    /**
     * Delete manager
     * @param integer $userId
     * @return array|\yii\web\Response
     * @throws \yii\base\Exception
     */
    public function run($userId)
    {
        $userModel = User::findOne($userId);
        $newPassword = \Yii::$app->security->generateRandomString(10);
        $userModel->password = \Yii::$app->security->generatePasswordHash($newPassword);
        $userModel->invite_code = \Yii::$app->security->generateRandomString(32);
        $userModel->invite_code_status = "ACTIVE";

        Yii::$app->response->format = Response::FORMAT_JSON;
        try {
            if (\Yii::$app->request->isAjax) {
                if ($userModel->save(false)) {
                    \Yii::$app->sendMail->run(
                        'sendLoginLink-html.php',
                        [
                            'email' => $userModel->email,
                            'phone_number' => $userModel->phone_number,
                            'loginLink' => \Yii::$app->urlManager->createAbsoluteUrl(
                                [
                                    'login',
                                    'invite_code' => $userModel->invite_code
                                ]
                            ),
                            'password' => $newPassword
                        ],
                        \Yii::$app->params['supportEmail'], $userModel->email, 'ConfirmRegistration'
                    );
                    return ['status' => 200, 'message' => Yii::t('app', 'Message was successfully send.')];
                }
            }
        } catch (\Exception $e) {
            Yii::error(ErrorHandler::convertExceptionToString($e));
            Yii::$app->response->setStatusCode(500);
            return  ['message' => Yii::t('app', 'Something wrong, please try again later.')];
        }
        return $this->controller->redirect(Url::to('index'));

    }
}

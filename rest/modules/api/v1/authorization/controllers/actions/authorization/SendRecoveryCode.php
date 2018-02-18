<?php
/**
 * Created by PhpStorm.
 * User: dima
 * Date: 16.02.18
 * Time: 7:30
 */

namespace rest\modules\api\v1\authorization\controllers\actions\authorization;

use common\behaviors\ValidatePostParameters;
use rest\modules\api\v1\authorization\models\RestUserEntity;
use Yii;
use yii\{rest\Action,web\Response,web\ServerErrorHttpException};

class SendRecoveryCode extends Action
{

    public function run()
    {
        $email = \Yii::$app->request->post('email');
        $phoneNumber = \Yii::$app->request->post('phone_number');
        $recoveryCode = rand(1000,9999);
        $user = new RestUserEntity();
        if(!empty($email)) {
            $user = $user->getUserByEmail($email);
        }elseif (!empty($phoneNumber)){
            $user = $user->getUserByPhoneNumber($phoneNumber);
        }
        $user->recovery_code = $recoveryCode;
        $user->created_recovery_code = time();

        if(!empty($email)) {
            Yii::$app->sendMail->run('@common/views/mail/sendSecurityCode-html.php',
                ['email' => $email, 'recoveryCode' => $user->recovery_code],
                Yii::$app->params['supportEmail'], $email,'Востановление пароля'
            );
        }elseif (!empty($phoneNumber)){
            Yii::$app->sendSms->run('Ваш код востановления пароля ,'. $user->recovery_code.' он будет активен в течении часа',
                $phoneNumber);
        }
        if ($user->save(false)) {
            $response = Yii::$app->getResponse();
            $response->setStatusCode('200', 'OK');
            $response->format = Response::FORMAT_JSON;
            return $response->content = [
                'status'  => $response->statusCode,
                'message' => 'Отправка кода восстановления прошло успешно'
            ];
        }

        throw new ServerErrorHttpException('Произошла ошибка при отправке кода восстановления!');
    }

}
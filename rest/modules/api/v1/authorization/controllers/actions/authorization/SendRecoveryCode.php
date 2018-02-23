<?php
/**
 * Created by PhpStorm.
 * User: dima
 * Date: 16.02.18
 * Time: 7:30
 */

namespace rest\modules\api\v1\authorization\controllers\actions\authorization;

use rest\behaviors\ResponseBehavior;
use rest\modules\api\v1\authorization\models\RestUserEntity;
use Yii;
use yii\{
    rest\Action, web\BadRequestHttpException, web\ServerErrorHttpException
};

/**
 * Class SendRecoveryCode
 * @package rest\modules\api\v1\authorization\controllers\actions\authorization
 */
class SendRecoveryCode extends Action
{
    /**
     * @return array
     */
    public function behaviors(): array
    {
        $behaviors = parent::behaviors();

        $behaviors['responseBehavior'] = ResponseBehavior::className();

        return $behaviors;
    }

    /**
     * @return mixed
     * @throws BadRequestHttpException
     * @throws ServerErrorHttpException
     */
    public function run()
    {
        $email = \Yii::$app->request->post('email');
        $phoneNumber = \Yii::$app->request->post('phone_number');

        $recoveryCode = rand(1000,9999);
        $user = new RestUserEntity();
        if (!empty($email)) {
            $user = $user->getUserByEmail($email);
        } elseif (!empty($phoneNumber)) {
            $user = $user->getUserByPhoneNumber($phoneNumber);
        } else {
            throw new BadRequestHttpException('Укажите email или номер телефона.');
        }
        $user->recovery_code = $recoveryCode;
        $user->created_recovery_code = time();

        if (!empty($email)) {
            Yii::$app->sendMail->run('@common/views/mail/sendSecurityCode-html.php',
                ['email' => $email, 'recoveryCode' => $user->recovery_code],
                Yii::$app->params['supportEmail'],
                $email,
                'Востановление пароля'
            );
        } elseif (!empty($phoneNumber)) {
            Yii::$app->sendSms->run('Ваш код востановления пароля ,'. $user->recovery_code.' он будет активен в течении часа',
                $phoneNumber
            );
        }
        if ($user->save(false)) {
            return $this->setResponse(
                200, 'Отправка кода восстановления прошло успешно'
            );
        }

        throw new ServerErrorHttpException('Произошла ошибка при отправке кода восстановления!');
    }

}
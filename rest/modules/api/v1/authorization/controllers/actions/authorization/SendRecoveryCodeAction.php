<?php
/**
 * Created by PhpStorm.
 * User: dima
 * Date: 16.02.18
 * Time: 7:30
 */

namespace rest\modules\api\v1\authorization\controllers\actions\authorization;

use rest\behaviors\ResponseBehavior;
use rest\modules\api\v1\authorization\controllers\AuthorizationController;
use rest\modules\api\v1\authorization\models\RestUserEntity;
use Yii;
use yii\{
    rest\Action, web\BadRequestHttpException, web\ServerErrorHttpException
};

/**
 * Class SendRecoveryCode
 * @package rest\modules\api\v1\authorization\controllers\actions\authorization
 */
class SendRecoveryCodeAction extends Action
{
    /** @var  AuthorizationController */
    public $controller;

    /**
     * Send recovery code action
     *
     * @SWG\Post(path="/authorization/send recovery code",
     *      tags={"Authorization module"},
     *      summary="User send recovery code",
     *      description="User send recovery code",
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *          in = "formData",
     *          name = "email",
     *          description = "User email",
     *          required = false,
     *          type = "string"
     *      ),
     *      @SWG\Parameter(
     *          in = "formData",
     *          name = "phone_number",
     *          description = "User phone number",
     *          required = false,
     *          type = "string"
     *      ),
     *      @SWG\Response(
     *         response = 200,
     *         description = "success",
     *         @SWG\Schema(
     *              type="object",
     *              @SWG\Property(property="status", type="integer", description="Status code"),
     *              @SWG\Property(property="message", type="string", description="Status message"),
     *               "data": {
     *              }
     *         ),
     *         examples = {
     *              "status": 200,
     *              "message": "Отправка кода восстановления прошло успешно.",
     *              "data": {
     *              }
     *         }
     *     ),
     *     @SWG\Response (
     *         response = 400,
     *         description = "Validation Error"
     *     ),
     *     @SWG\Response(
     *         response = 500,
     *         description = "Internal Server Error"
     *     )
     * )
     *
    /**
     * Send recovery code action
     *
     * @return array
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
            Yii::$app->sendSms->run('Ваш код востановления пароля ,' .$user->recovery_code. ' он будет активен в течении часа',
                $phoneNumber
            );
        }
        if ($user->save(false)) {
            /** @var ResponseBehavior */
            return $this->controller->setResponse(
                200, 'Отправка кода восстановления прошло успешно'
            );
        }

        throw new ServerErrorHttpException('Произошла ошибка при отправке кода восстановления!');
    }

}
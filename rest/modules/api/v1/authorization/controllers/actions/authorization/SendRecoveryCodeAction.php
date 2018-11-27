<?php
/**
 * Created by PhpStorm.
 * User: dima
 * Date: 16.02.18
 * Time: 7:30
 */

namespace rest\modules\api\v1\authorization\controllers\actions\authorization;

use common\behaviors\ValidatePostParameters;
use rest\modules\api\v1\authorization\controllers\AuthorizationController;
use rest\modules\api\v1\authorization\models\RestUserEntity;
use Yii;
use yii\{
    rest\Action,
    web\NotFoundHttpException,
    web\ServerErrorHttpException
};

/**
 * Class SendRecoveryCode
 * @package rest\modules\api\v1\authorization\controllers\actions\authorization
 *
 * @mixin ValidatePostParameters
 */
class SendRecoveryCodeAction extends Action
{
    /** @var  AuthorizationController */
    public $controller;

    /**
     * @var array
     */
    public $params = [];

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'reportParams' => [
                'class'       => ValidatePostParameters::class,
                'inputParams' => ['phone_number']
            ]
        ];
    }

    /**
     * @inheritdoc
     */
    protected function beforeRun()
    {
        $this->validationParams();

        return parent::beforeRun();
    }

    /**
     * Send recovery code action
     *
     * @SWG\Post(path="/authorization/send-recovery-code",
     *      tags={"Authorization module"},
     *      summary=" send recovery code",
     *      description="Send code to recovery",
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *          in = "formData",
     *          name = "phone_number",
     *          description = "User phone number",
     *          required = true,
     *          type = "string"
     *      ),
     *      @SWG\Response(
     *         response = 200,
     *         description = "success",
     *         @SWG\Schema(
     *              type="object",
     *              @SWG\Property(property="status", type="integer", description="Status code"),
     *              @SWG\Property(property="message", type="string", description="Status message"),
     *              @SWG\Property(property="data", type="object")
     *         ),
     *         examples = {
     *              "status": 200,
     *              "message": "Recovery code was successfully send",
     *         }
     *     ),
     *     @SWG\Response (
     *         response = 404,
     *         description = "User is not found"
     *     ),
     *     @SWG\Response(
     *         response = 500,
     *         description = "Server Error"
     *     )
     * )
     *
     *
     * Send recovery code to user
     *
     * @return array
     * @throws NotFoundHttpException
     * @throws ServerErrorHttpException
     */
    public function run()
    {
        $phoneNumber = \Yii::$app->request->post('phone_number');
        $recoveryCode = '0000'; //rand(1000, 9999);

        $user = new RestUserEntity();
        if (($user = $user->findByPhoneNumber($phoneNumber)) === null) {
            throw new NotFoundHttpException(Yii::t('app', 'User is not found'));
        }

        $attributes = ['recovery_code', 'created_recovery_code'];
        try {
            $user->recovery_code = $recoveryCode;
            $user->created_recovery_code = time();

//            Yii::$app->sendSms->run(
//                'Ваш код востановления пароля, ' . $user->recovery_code . ' он будет активен в течении часа',
//                $phoneNumber
//            );

            if ($user->save(false, $attributes)) {
                $response = \Yii::$app->getResponse()->setStatusCode(200);
                return $response->content = [
                    'status'  => $response->statusCode,
                    'message' => Yii::t('app', 'Recovery code was successfully send')
                ];

            }
            throw new ServerErrorHttpException();
        } catch (\Exception $e) {
            throw new ServerErrorHttpException('Something is wrong, please try again later.');
        }
    }
}

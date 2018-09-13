<?php

namespace rest\modules\api\v1\authorization\controllers\actions\authorization;

use common\behaviors\ValidatePostParameters;
use rest\modules\api\v1\authorization\controllers\AuthorizationController;
use rest\modules\api\v1\authorization\models\RestUserEntity;
use yii\rest\Action;

/**
 * Class ResendVerificationCodeAction
 * @package rest\modules\api\v1\authorization\controllers\actions\authorization
 * @mixin ValidatePostParameters
 */
class ResendVerificationCodeAction extends Action
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
     * @return bool
     * @throws \yii\base\InvalidConfigException
     * @throws \yii\web\BadRequestHttpException
     */
    protected function beforeRun()
    {
        $this->validationParams();

        return parent::beforeRun();
    }

    /**
     * Creates and sends new verification code to user
     *
     * @SWG\Post(path="/authorization/resend-verification-code",
     *      tags={"Authorization module"},
     *      summary=" send verification code",
     *      description="Creates and sends new verification code to user",
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
     *         examples = {
     *              "status": 200,
     *              "message": "Код верификации успешно отправлен."
     *         }
     *     ),
     *     @SWG\Response (
     *         response = 400,
     *         description = "Bad request"
     *     ),
     *     @SWG\Response (
     *         response = 404,
     *         description = "User not found"
     *     ),
     *     @SWG\Response(
     *         response = 500,
     *         description = "Internal Server Error"
     *     )
     * )*
     * @return array
     */
    public function run() {
        /** @var $model RestUserEntity */
        $model = new $this->modelClass();
        if ($model->resendVerificationCode(\Yii::$app->request->post('phone_number'))) {
            return [
                'status'   => 200,
                'message'  => 'Код верификации успешно отправлен.'
            ];
        }
    }
}
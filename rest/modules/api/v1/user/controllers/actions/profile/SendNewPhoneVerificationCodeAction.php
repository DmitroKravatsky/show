<?php

namespace rest\modules\api\v1\user\controllers\actions\profile;

use common\behaviors\ValidatePostParameters;
use rest\modules\api\v1\authorization\models\RestUserEntity;
use rest\modules\api\v1\user\controllers\UserProfileController;
use yii\rest\Action;
use yii\web\BadRequestHttpException;
use yii\web\NotFoundHttpException;
use yii\web\ServerErrorHttpException;

/**
 * Class SendNewPhoneValidationCodeAction
 * @package rest\modules\api\v1\user\controllers\actions\profile
 *
 * @mixin ValidatePostParameters
 */

class SendNewPhoneVerificationCodeAction extends Action
{
    /** @var  UserProfileController $controller */
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
     * @throws BadRequestHttpException
     * @throws \yii\base\InvalidConfigException
     */
    protected function beforeRun()
    {
        $this->validationParams();

        return parent::beforeRun();
    }

    /**
     * Sends code to verify new phone number
     *
     * @SWG\POST(path="/user/profile/send-new-phone-verification-code",
     *      tags={"User module"},
     *      summary="Send verification code",
     *      description="Send verification code to users new phone",
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *        in = "header",
     *        name = "Authorization",
     *        description = "Authorization: Bearer &lt;token&gt;",
     *        required = true,
     *        type = "string"
     *      ),
     *      @SWG\Parameter(
     *          in = "formData",
     *          name = "phone_number",
     *          description = "user's new phone",
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
     *         ),
     *         examples = {
     *              "status": 200,
     *              "message": "Verification code was successfully send to your new number",
     *         }
     *     ),
     *     @SWG\Response (
     *         response = 400,
     *         description = "Not enough income parameters"
     *     ),
     *     @SWG\Response (
     *         response = 401,
     *         description = "Invalid credentials or Expired token"
     *     ),
     *     @SWG\Response (
     *         response = 404,
     *         description = "NotFoundHttpException"
     *     ),
     *     @SWG\Response (
     *         response = 500,
     *         description = "ServerErrorHttpException"
     *     )
     * )
     *
     * @return array
     *
     * @throws NotFoundHttpException
     * @throws ServerErrorHttpException
     */
    public function run()
    {
        try {
            /** @var RestUserEntity $user */
            $user = new $this->modelClass;
            $user->sendPhoneVerificationCode(\Yii::$app->request->getBodyParam('phone_number'));
            $response = \Yii::$app->getResponse()->setStatusCode(200);

            return [
                'status' => $response->statusCode,
                'message' => \Yii::t('app', 'Verification code was successfully send'),
            ];
        } catch (NotFoundHttpException $e) {
            throw new NotFoundHttpException($e->getMessage());
        } catch (ServerErrorHttpException $e) {
            throw new ServerErrorHttpException($e->getMessage());
        }
    }
}
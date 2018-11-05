<?php

namespace rest\modules\api\v1\user\controllers\actions\profile;

use common\behaviors\ValidatePostParameters;
use rest\modules\api\v1\authorization\models\RestUserEntity;
use rest\modules\api\v1\user\controllers\UserProfileController;
use yii\rest\Action;
use yii\web\BadRequestHttpException;
use yii\web\NotFoundHttpException;

/**
 * Class VerifyNewPhoneAction
 * @package rest\modules\api\v1\user\controllers\actions\profile
 *
 * @mixin ValidatePostParameters
 */

class VerifyNewPhoneAction extends Action
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
                'inputParams' => ['phone_number', 'phone_verification_code']
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
     * Sends code to validate new phone
     *
     * @SWG\POST(path="/user/profile/verify-new-phone",
     *      tags={"User module"},
     *      summary="Verify new phone",
     *      description="Verify user new phone number",
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
     *          description = "user's new phone_number",
     *          required = true,
     *          type = "string"
     *      ),
     *     @SWG\Parameter(
     *          in = "formData",
     *          name = "phone_number_verification_code",
     *          description = "user's new phone_number verification code",
     *          required = true,
     *          type = "integer"
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
     *              "message": "Phone was updated .",
     *         }
     *     ),
     *     @SWG\Response(
     *         response = 404,
     *         description = "User is not found"
     *     ),
     *      @SWG\Response(
     *         response = 405,
     *         description = "Method Not Allowed"
     *     ),
     *     @SWG\Response (
     *         response = 422,
     *         description = "Wrong verification_code"
     *     ),
     *     @SWG\Response(
     *         response = 500,
     *         description = "Internal sever error"
     *     )
     * )
     *
     * @throws NotFoundHttpException
     * @return array
     */
    public function run()
    {
        try {
            /** @var RestUserEntity $user */
            $user = new $this->modelClass;
            $user->verifyNewPhone(\Yii::$app->request->getBodyParams());
            $response = \Yii::$app->getResponse()->setStatusCode(200);

            return [
                'status' => $response->statusCode,
                'message' => \Yii::t('app', 'Phone was updated .'),
            ];
        } catch (NotFoundHttpException $e) {
            throw new NotFoundHttpException($e->getMessage());
        }
    }
}
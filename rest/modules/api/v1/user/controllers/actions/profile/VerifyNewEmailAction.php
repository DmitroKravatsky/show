<?php

namespace rest\modules\api\v1\user\controllers\actions\profile;

use common\behaviors\ValidatePostParameters;
use rest\modules\api\v1\authorization\models\RestUserEntity;
use rest\modules\api\v1\user\controllers\UserProfileController;
use yii\rest\Action;
use yii\web\BadRequestHttpException;
use yii\web\NotFoundHttpException;

/**
 * Class VerifyNewEmailAction
 * @package rest\modules\api\v1\user\controllers\actions\profile
 *
 * @mixin ValidatePostParameters
 */

class VerifyNewEmailAction extends Action
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
                'inputParams' => ['email', 'email_verification_code']
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
     * Sends code to validate new email
     *
     * @SWG\POST(path="/user/profile/verify-new-email",
     *      tags={"User module"},
     *      summary="Get user profile",
     *      description="Get user profile",
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
     *          name = "email",
     *          description = "user's email",
     *          required = true,
     *          type = "string"
     *      ),
     *     @SWG\Parameter(
     *          in = "formData",
     *          name = "email_verification_code",
     *          description = "user's new email verification code",
     *          required = true,
     *          type = "integer"
     *      ),
     *      @SWG\Response(
     *         response = 201,
     *         description = "success",
     *         @SWG\Schema(
     *              type="object",
     *              @SWG\Property(property="status", type="integer", description="Status code"),
     *              @SWG\Property(property="message", type="string", description="Status message"),
     *              @SWG\Property(property="data", type="object")
     *         ),
     *         examples = {
     *              "status": 200,
     *              "message": "Email was updated ",
     *              "data": {
     *                  "id" : 21,
     *                  "access_token": "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJqdGkiOjExLCJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiIsImV4cCI6MTUxODE3MjA2NX0.YpKRykzIfEJI5RhB5HYd5pDdBy8CWrA5OinJYGyVmew",
     *                  "exp": 1536224824,
     *                  "refresh_token": "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpZCI6MTI1LCJleHAiOjE1MjcxNjk2NDV9.INeMCEZun9wQ4xgeDSJpcae6aV8p3F7JTgoIGzv5QHk",
     *              }
     *         }
     *     ),
     *     @SWG\Response(
     *         response = 404,
     *         description = "User is not found"
     *     ),
     *     @SWG\Response (
     *         response = 422,
     *         description = "Wrong verification_code"
     *     ),
     *     @SWG\Response(
     *         response = 500,
     *         description = "Sever Error"
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
            $user->verifyNewEmail(\Yii::$app->request->getBodyParams());
            $response = \Yii::$app->getResponse()->setStatusCode(200);

            return [
                'status' => $response->statusCode,
                'message' => \Yii::t('app', 'Email was updated .'),
            ];
        } catch (NotFoundHttpException $e) {
            throw new NotFoundHttpException($e->getMessage());
        }
    }
}
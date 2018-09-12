<?php
/**
 * Created by PhpStorm.
 * User: dima
 * Date: 02.03.18
 * Time: 22:16
 */

namespace rest\modules\api\v1\authorization\controllers\actions\authorization;

use rest\modules\api\v1\authorization\controllers\AuthorizationController;
use rest\modules\api\v1\authorization\models\RestUserEntity;
use yii\rest\Action;
use yii\web\NotFoundHttpException;
use yii\web\ServerErrorHttpException;
use yii\web\UnprocessableEntityHttpException;

class VerificationProfileAction extends Action
{
    /** @var  AuthorizationController */
    public $controller;

    /**
     * VerificationProfile action
     *
     * @SWG\Post(path="/authorization/verification-profile",
     *      tags={"Authorization module"},
     *      summary="User verification-profile",
     *      description="User verification-profile",
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *        in = "formData",
     *        name = "phone_number",
     *        description = "User phone number",
     *        required = true,
     *        type = "string"
     *      ),
     *      @SWG\Parameter(
     *          in = "formData",
     *          name = "verification_code",
     *          description = "User verification code",
     *          required = true,
     *          type = "string"
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
     *              "message": "Верификация профиля проша успешно.",
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
     *         description = "Internal sever error"
     *     )
     * )
     *
     *
     * Verify user's account
     *
     * @return array
     * @throws NotFoundHttpException
     * @throws UnprocessableEntityHttpException
     * @throws ServerErrorHttpException
     */
    public function run()
    {
        /** @var RestUserEntity $model */
        $model = new $this->modelClass;
        $user  = $model->verifyUser(\Yii::$app->request->bodyParams);

        $response = \Yii::$app->getResponse()->setStatusCode(200);
        return [
            'status'  => $response->statusCode,
            'message' => 'Верификация профиля прошла успешно.',
            'data'    => [
                /** @var RestUserEntity $user */
                'id'            => $user->id,
                'access_token'  => $token = $user->getJWT(['user_id' => $user->id]),
                'exp'           => RestUserEntity::getPayload($token, 'exp'),
                'refresh_token' => $user->refresh_token,
            ]
        ];
    }
}
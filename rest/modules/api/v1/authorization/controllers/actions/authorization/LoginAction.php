<?php

namespace rest\modules\api\v1\authorization\controllers\actions\authorization;

use rest\modules\api\v1\authorization\controllers\AuthorizationController;
use rest\modules\api\v1\authorization\models\RestUserEntity;
use yii\rest\Action;
use yii\web\ForbiddenHttpException;
use yii\web\NotFoundHttpException;
use yii\web\ServerErrorHttpException;
use yii\web\UnauthorizedHttpException;
use yii\web\UnprocessableEntityHttpException;

/**
 * Class LoginAction
 * @package rest\modules\api\v1\authorization\controllers\actions\authorization
 */
class LoginAction extends Action
{
    /** @var  AuthorizationController */
    public $controller;

    /**
     * Login action
     * 
     * @SWG\Post(path="/authorization/login",
     *      tags={"Authorization module"},
     *      summary="User login",
     *      description="User login",
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *          in = "formData",
     *          name = "phone_number",
     *          description = "User phone number",
     *          required = true,
     *          type = "string"
     *      ),
     *      @SWG\Parameter(
     *          in = "formData",
     *          name = "password",
     *          description = "User password",
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
     *              @SWG\Property(property="data", type="object",
     *                  @SWG\Property(property="id", type="integer", description="user_id"),
     *                  @SWG\Property(property="access_token", type="string", description="access token"),
     *                  @SWG\Property(property="refresh_token", type="string", description="refresh token")
     *              ),
     *         ),
     *         examples = {
     *              "status": 200,
     *              "message": "Authorization was successfully ended",
     *              "data": {
     *                  "user_id" : "157",
     *                  "access_token": "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJqdGkiOjExLCJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiIsImV4cCI6MTUxODE3MjA2NX0.YpKRykzIfEJI5RhB5HYd5pDdBy8CWrA5OinJYGyVmew",
     *                  "exp": 1536224824,
     *                  "refresh_token": "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpZCI6MTI1LCJleHAiOjE1MjcxNjk2NDV9.INeMCEZun9wQ4xgeDSJpcae6aV8p3F7JTgoIGzv5QHk"
     *              }
     *         }
     *     ),
     *      @SWG\Response (
     *         response = 401,
     *         description = "Wrong credentials"
     *     ),
     *      @SWG\Response(
     *         response = 404,
     *         description = "User not found"
     *     ),
     *     @SWG\Response (
     *         response = 422,
     *         description = "Validation Error"
     *     ),
     *     @SWG\Response (
     *         response = 500,
     *         description = "Internal Error"
     *     )
     * )
     *
     * Login action
     *
     * @return array
     *
     * @throws NotFoundHttpException
     * @throws UnauthorizedHttpException
     * @throws UnprocessableEntityHttpException
     * @throws ForbiddenHttpException
     * @throws ServerErrorHttpException
     */
    public function run()
    {
        try {
            /** @var RestUserEntity $userModel */
            $userModel = new $this->modelClass;

            if ($user = $userModel->login(\Yii::$app->request->bodyParams)) {
                $user->created_refresh_token = time();
                $user->refresh_token = $user->getRefreshToken(['user_id' => $user->id]);
                $user->verifyUserAfterLogin();

                if (!$user->save(false)) {
                    throw new ServerErrorHttpException();
                }
                $response = \Yii::$app->getResponse()->setStatusCode(200);

                return [
                    'status'  => $response->statusCode,
                    'message' => 'Authorization was successfully ended',
                    'data'    => [
                        'user_id'       => $user->id,
                        'access_token'  => $accessToken = $user->getJWT(['user_id' => $user->id]),
                        'exp'           => RestUserEntity::getPayload($accessToken, 'exp'),
                        'refresh_token' => $user->refresh_token
                    ]
                ];
            }
            throw new UnauthorizedHttpException();
        } catch (UnprocessableEntityHttpException $e) {
            throw new UnprocessableEntityHttpException($e->getMessage());
        } catch (ForbiddenHttpException $e) {
            throw new ForbiddenHttpException($e->getMessage());
        } catch (NotFoundHttpException $e) {
            throw new NotFoundHttpException($e->getMessage());
        } catch (UnauthorizedHttpException $e) {
            throw new UnauthorizedHttpException('Wrong credentials');
        } catch (ServerErrorHttpException $e) {
            throw new ServerErrorHttpException('Something is wrong, please try again later');
        }
    }
}

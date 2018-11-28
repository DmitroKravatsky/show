<?php

declare(strict_types=1);

namespace rest\modules\api\v1\authorization\controller\action\authorization;

use Yii;
use yii\rest\Action;
use yii\web\{
    HttpException, NotFoundHttpException, ServerErrorHttpException, UnauthorizedHttpException,
    ErrorHandler
};
use rest\modules\api\v1\authorization\model\authorization\GenerateNewAccessTokenRequestModel;
use rest\modules\api\v1\authorization\controller\AuthorizationController;

class GenerateNewAccessTokenAction extends Action
{
    /** @var  AuthorizationController */
    public $controller;

    /**
     * GenerateNewAccessToken action
     *
     * @SWG\Post(path="/authorization/generate-new-access-token",
     *      tags={"Authorization module"},
     *      summary="Generate-new-access-token for user",
     *      description="Generate new access_token for user",
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *          in = "formData",
     *          name = "refresh_token",
     *          description = "User refresh_token",
     *          required = true,
     *          type = "string"
     *      ),
     *      @SWG\Response(
     *         response = 201,
     *         description = "created",
     *         @SWG\Schema(
     *              type="object",
     *              @SWG\Property(property="status", type="integer", description="Status code"),
     *              @SWG\Property(property="message", type="string", description="Status message"),
     *              @SWG\Property(property="data", type="object",
     *                  @SWG\Property(property="access_token",  type="string",  description="access token"),
     *                  @SWG\Property(property="refresh_token", type="string",  description="refresh token"),
     *                  @SWG\Property(property="exp",           type="int",     description="time of token expiration"),
     *                  @SWG\Property(property="data", type="object",
     *                      @SWG\Property(property="id",            type="integer", description="user id"),
     *                      @SWG\Property(property="phone_number",  type="string",  description="user's phone number"),
     *                      @SWG\Property(property="role",          type="string",  description="user's role"),
     *                      @SWG\Property(property="created_at",    type="string",  description="creation time"),
     *                      @SWG\Property(property="status",        type="string",  description="user profile status")
     *                  ),
     *              ),
     *         ),
     *         examples = {
     *              "status": 201,
     *              "message": "New access token was successfully generated.",
     *              "data": {
     *                  "access_token": "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJqdGkiOjExLCJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiIsImV4cCI6MTUxODE3MjA2NX0.YpKRykzIfEJI5RhB5HYd5pDdBy8CWrA5OinJYGyVmew",
     *                  "refresh_token": "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpZCI6MTI1LCJleHAiOjE1MjcxNjk2NDV9.INeMCEZun9wQ4xgeDSJpcae6aV8p3F7JTgoIGzv5QHk",
     *                  "exp": "1520070475",
     *                  "user": {
     *                      "id": 5,
     *                      "phone_number": "+380959751856",
     *                      "role": "guest",
     *                      "created_at": 1520070475,
     *                      "status": "VERIFIED"
     *                  }
     *              }
     *         }
     *     ),
     *     @SWG\Response(
     *         response = 401,
     *         description = "Refresh token was expired"
     *     ),
     *     @SWG\Response(
     *         response = 404,
     *         description = "User is not found"
     *     ),
     *     @SWG\Response (
     *         response = 422,
     *         description = "Validation Error"
     *     ),
     *     @SWG\Response(
     *         response = 500,
     *         description = "Server Error"
     *     )
     * )
     *
     *
     * Generate New Access Token
     *
     * @return array
     * @throws HttpException
     * @throws NotFoundHttpException
     * @throws ServerErrorHttpException
     */
    public function run()
    {
        $model = new GenerateNewAccessTokenRequestModel();
        if (!$model->load(Yii::$app->request->bodyParams, '') || !$model->validate()) {
            $model->throwModelException($model->errors);
        }

        try {
            $response = Yii::$app->getResponse()->setStatusCode(201);
            return [
                'status' => $response->statusCode,
                'message' => 'New access token was successfully generated.',
                'data' => $this->controller->service->generateNewAccessToken($model)
            ];
        } catch (NotFoundHttpException $e) {
            throw new NotFoundHttpException($e->getMessage());
        } catch (UnauthorizedHttpException $e) {
            throw new UnauthorizedHttpException($e->getMessage());
        } catch (\Exception $e) {
            Yii::error(ErrorHandler::convertExceptionToString($e));
            throw new ServerErrorHttpException('Something is wrong, please try again later.');
        }
    }
}

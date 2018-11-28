<?php

declare(strict_types=1);

namespace rest\modules\api\v1\authorization\controller\action\authorization;

use Yii;
use rest\modules\api\v1\authorization\controller\AuthorizationController;
use rest\modules\api\v1\authorization\model\authorization\LoginRequestModel;
use yii\rest\Action;
use yii\web\{
    ForbiddenHttpException, NotFoundHttpException, UnauthorizedHttpException,
    UnprocessableEntityHttpException, ServerErrorHttpException, ErrorHandler
};

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
     *         description = "OK",
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
     *              "message": "Authorization was successfully ended.",
     *              "data": {
     *                  "user_id" : 157,
     *                  "access_token": "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJqdGkiOjExLCJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiIsImV4cCI6MTUxODE3MjA2NX0.YpKRykzIfEJI5RhB5HYd5pDdBy8CWrA5OinJYGyVmew",
     *                  "exp": 1536224824,
     *                  "refresh_token": "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpZCI6MTI1LCJleHAiOjE1MjcxNjk2NDV9.INeMCEZun9wQ4xgeDSJpcae6aV8p3F7JTgoIGzv5QHk"
     *              }
     *         }
     *     ),
     *      @SWG\Response (
     *         response = 401,
     *         description = "Unauthorized"
     *     ),
     *      @SWG\Response(
     *         response = 404,
     *         description = "Not Found"
     *     ),
     *     @SWG\Response (
     *         response = 422,
     *         description = "Unprocessable Entity"
     *     ),
     *     @SWG\Response (
     *         response = 500,
     *         description = "Internal Server Error"
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
        $model = new LoginRequestModel();
        if (!$model->load(Yii::$app->request->bodyParams, '') || !$model->validate()) {
            $model->throwModelException($model->errors);
        }

        try {
            return [
                'status'  => Yii::$app->response->getStatusCode(),
                'message' => 'Authorization was successfully ended.',
                'data'    => $this->controller->service->login($model)
            ];
        } catch (ForbiddenHttpException $e) {
            throw new ForbiddenHttpException($e->getMessage());
        } catch (NotFoundHttpException $e) {
            throw new NotFoundHttpException($e->getMessage());
        } catch (UnauthorizedHttpException $e) {
            throw new UnauthorizedHttpException($e->getMessage());
        } catch (\Exception $e) {
            Yii::error(ErrorHandler::convertExceptionToString($e));
            throw new ServerErrorHttpException('Something is wrong, please try again later');
        }
    }
}

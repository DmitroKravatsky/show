<?php

declare(strict_types=1);

namespace rest\modules\api\v1\authorization\controller\action\authorization;

use Yii;
use rest\modules\api\v1\authorization\controller\AuthorizationController;
use rest\modules\api\v1\authorization\model\authorization\VerificationProfileRequestModel;
use yii\rest\Action;
use yii\web\{
    NotFoundHttpException, UnprocessableEntityHttpException, ServerErrorHttpException,
    ErrorHandler
};

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
     *              @SWG\Property(property="data", type="object",
     *                  @SWG\Property(property="id", type="int", description="user id"),
     *                  @SWG\Property(property="access_token", type="string", description="access token"),
     *                  @SWG\Property(property="exp", type="integer", description="expires date"),
     *                  @SWG\Property(property="refresh_token", type="string", description="refresh token"),
     *              )
     *         ),
     *         examples = {
     *              "status": 200,
     *              "message": "Profile verification was successfully ended",
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
     *         description = "Validation Error"
     *     ),
     *     @SWG\Response(
     *         response = 500,
     *         description = "Sever Error"
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
        try {
            $model = new VerificationProfileRequestModel();
            if (!$model->load(Yii::$app->request->bodyParams, '') || !$model->validate()) {
                $model->throwModelException($model->errors);
            }

            return [
                'status' => Yii::$app->getResponse()->getStatusCode(),
                'message' => 'Profile verification was successfully ended',
                'data' => $this->controller->service->verifyUser($model)
            ];
        } catch (NotFoundHttpException $e) {
            throw new NotFoundHttpException($e->getMessage());
        } catch (UnprocessableEntityHttpException $e) {
            throw new UnprocessableEntityHttpException($e->getMessage());
        } catch (\Exception $e) {
            Yii::error(ErrorHandler::convertExceptionToString($e));
            throw new ServerErrorHttpException('Something is wrong, please try again later.');
        }
    }
}

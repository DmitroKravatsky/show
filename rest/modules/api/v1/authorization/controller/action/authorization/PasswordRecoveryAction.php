<?php

declare(strict_types=1);

namespace rest\modules\api\v1\authorization\controller\action\authorization;

use Yii;
use rest\modules\api\v1\authorization\model\authorization\PasswordRecoveryRequestModel;
use rest\modules\api\v1\authorization\controller\AuthorizationController;
use yii\rest\Action;
use yii\web\{
    BadRequestHttpException, HttpException, NotFoundHttpException, ErrorHandler,
    UnprocessableEntityHttpException, ServerErrorHttpException
};

class PasswordRecoveryAction extends Action
{
    /** @var  AuthorizationController */
    public $controller;

    /**
     * Password recovery action
     *
     * @SWG\Post(path="/authorization/password-recovery",
     *      tags={"Authorization module"},
     *      summary="User password recovery",
     *      description="User password-recovery",
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
     *          description = "User new password",
     *          required = true,
     *          type = "string"
     *      ),
     *      @SWG\Parameter(
     *          in = "formData",
     *          name = "confirm_password",
     *          description = "User password confirmation",
     *          required = true,
     *          type = "string"
     *      ),
     *      @SWG\Parameter(
     *          in = "formData",
     *          name = "recovery_code",
     *          description = "User password recovery code",
     *          required = true,
     *          type = "integer"
     *      ),
     *      @SWG\Response(
     *         response = 200,
     *         description = "OK",
     *         @SWG\Schema(
     *              type="object",
     *              @SWG\Property(property="status", type="integer", description="Status code"),
     *              @SWG\Property(property="message", type="string", description="Status message"),
     *         ),
     *         examples = {
     *              "status": 200,
     *              "message": "Password recovery was successfully ended",
     *         }
     *     ),
     *     @SWG\Response (
     *         response = 404,
     *         description = "Not Found"
     *     ),
     *     @SWG\Response (
     *         response = 422,
     *         description = "Unprocessable Entity"
     *     ),
     *     @SWG\Response(
     *         response = 500,
     *         description = "Internal Server Error"
     *     )
     * )
     *
     *
     * Password recovery action
     *
     * @return array
     * @throws BadRequestHttpException
     * @throws HttpException
     */
    public function run()
    {
        try {
            $model = new PasswordRecoveryRequestModel();
            if (!$model->load(Yii::$app->request->bodyParams, '') || !$model->validate()) {
                $model->throwModelException($model->errors);
            }

            $this->controller->service->passwordRecovery($model);

            return [
                'status' => Yii::$app->response->getStatusCode(),
                'message' => 'Password recovery was successfully ended.'
            ];
        } catch (NotFoundHttpException $e) {
            throw new NotFoundHttpException($e->getMessage());
        } catch (UnprocessableEntityHttpException $e) {
            throw new UnprocessableEntityHttpException($e->getMessage());
        } catch (ServerErrorHttpException $e) {
            Yii::error(ErrorHandler::convertExceptionToString($e));
            throw new ServerErrorHttpException('Something is wrong, please try again later.');
        }
    }
}

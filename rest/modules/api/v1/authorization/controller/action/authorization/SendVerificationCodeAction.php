<?php

declare(strict_types=1);

namespace rest\modules\api\v1\authorization\controller\action\authorization;

use Yii;
use rest\modules\api\v1\authorization\controller\AuthorizationController;
use rest\modules\api\v1\authorization\model\authorization\ResendVerificationCodeRequestModel;
use yii\rest\Action;
use yii\web\{
    NotFoundHttpException, ServerErrorHttpException, ErrorHandler
};

class SendVerificationCodeAction extends Action
{
    /** @var  AuthorizationController */
    public $controller;

    /**
     * Creates and sends new verification code to user
     *
     * @SWG\Post(path="/authorization/send-verification-code",
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
     *              "message": "Verification code was successfully send."
     *         }
     *     ),
     *     @SWG\Response (
     *         response = 404,
     *         description = "Not Found"
     *     ),
     *     @SWG\Response(
     *         response = 500,
     *         description = "Internal Server Error"
     *     )
     * )*
     * @return array
     *
     * @throws NotFoundHttpException
     * @throws ServerErrorHttpException
     */
    public function run() {
        $model = new ResendVerificationCodeRequestModel();
        if (!$model->load(Yii::$app->request->bodyParams, '') || !$model->validate()) {
            $model->throwModelException($model->errors);
        }

        try {
            $this->controller->service->resendVerificationCode($model);

            return [
                'status' => Yii::$app->response->getStatusCode(),
                'message' => 'Verification code was successfully send.'
            ];
        } catch (NotFoundHttpException $e) {
            throw new NotFoundHttpException($e->getMessage());
        } catch (\Exception $e) {
            Yii::error(ErrorHandler::convertExceptionToString($e));
            throw new ServerErrorHttpException('Something is wrong, please try again later.');
        }
    }
}

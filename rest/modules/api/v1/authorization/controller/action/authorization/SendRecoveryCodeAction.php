<?php

declare(strict_types=1);

namespace rest\modules\api\v1\authorization\controller\action\authorization;

use Yii;
use rest\modules\api\v1\authorization\model\authorization\SendRecoveryCodeRequestModel;
use common\behaviors\ValidatePostParameters;
use rest\modules\api\v1\authorization\controller\AuthorizationController;
use yii\web\{
    NotFoundHttpException, ServerErrorHttpException, ErrorHandler
};
use yii\rest\Action;

/**
 * @mixin ValidatePostParameters
 */
class SendRecoveryCodeAction extends Action
{
    /** @var  AuthorizationController */
    public $controller;

    /**
     * Send recovery code action
     *
     * @SWG\Post(path="/authorization/send-recovery-code",
     *      tags={"Authorization module"},
     *      summary=" send recovery code",
     *      description="Send code to recovery",
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
     *         @SWG\Schema(
     *              type="object",
     *              @SWG\Property(property="status", type="integer", description="Status code"),
     *              @SWG\Property(property="message", type="string", description="Status message"),
     *              @SWG\Property(property="data", type="object")
     *         ),
     *         examples = {
     *              "status": 200,
     *              "message": "Recovery code was successfully send",
     *              "data": {
     *              }
     *         }
     *     ),
     *     @SWG\Response (
     *         response = 404,
     *         description = "User is not found"
     *     ),
     *     @SWG\Response (
     *         response = 422,
     *         description = "Unprocessable Entity"
     *     ),
     *     @SWG\Response(
     *         response = 500,
     *         description = "Server Error"
     *     )
     * )
     *
     *
     * Send recovery code to user
     *
     * @return array
     * @throws NotFoundHttpException
     * @throws ServerErrorHttpException
     */
    public function run()
    {
        $model = new SendRecoveryCodeRequestModel();
        if (!$model->load(Yii::$app->request->bodyParams, '') || !$model->validate()) {
            $model->throwModelException($model->errors);
        }

        try {
            $this->controller->service->sendPasswordRecoveryCode($model);

            return [
                'status' => Yii::$app->response->getStatusCode(),
                'message' => Yii::t('app', 'Recovery code was successfully send')
            ];
        } catch (NotFoundHttpException $e) {
            throw new NotFoundHttpException($e->getMessage());
        } catch (\Exception $e) {
            Yii::error(ErrorHandler::convertExceptionToString($e));
            throw new ServerErrorHttpException('Something is wrong, please try again later.');
        }
    }
}

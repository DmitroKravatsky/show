<?php

declare(strict_types=1);

namespace rest\modules\api\v1\authorization\controller\action\authorization;

use Yii;
use rest\modules\api\v1\authorization\model\authorization\RegisterRequestModel;
use rest\modules\api\v1\authorization\controller\AuthorizationController;
use yii\rest\Action;
use yii\web\{ ServerErrorHttpException, UnprocessableEntityHttpException };

class RegisterAction extends Action
{
    /** @var  AuthorizationController */
    public $controller;

    /**
     * Register action
     *
     * @SWG\Post(path="/authorization/register",
     *      tags={"Authorization module"},
     *      summary="User register",
     *      description="User register",
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
     *       @SWG\Parameter(
     *          in = "formData",
     *          name = "confirm_password",
     *          description = "User password",
     *          required = true,
     *          type = "string"
     *      ),
     *      @SWG\Parameter(
     *          in = "formData",
     *          name = "terms_condition",
     *          description = "Terms condition",
     *          required = true,
     *          type = "integer",
     *          enum = {0, 1}
     *      ),
     *      @SWG\Response(
     *         response = 201,
     *         description = "created",
     *         @SWG\Schema(
     *              type="object",
     *              @SWG\Property(property="status", type="integer", description="Status code"),
     *              @SWG\Property(property="message", type="string", description="Status message"),
     *              @SWG\Property(property="data", type="object",
     *                  @SWG\Property(property="id", type="integer", description="user id"),
     *                  @SWG\Property(property="phone_number", type="string", description="phone number"),
     *                  @SWG\Property(property="status", type="string", description="profile status"),
     *              ),
     *         ),
     *         examples = {
     *              "status": 201,
     *              "message": "Registration completed successfully.",
     *              "data": {
     *                  "id" : 21,
     *                  "phone_number": "+380939353498",
     *                  "status": "UNVERIFIED"
     *              }
     *         }
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
     * Register User action
     * 
     * @return array
     *
     * @throws UnprocessableEntityHttpException
     * @throws ServerErrorHttpException
     */
    public function run()
    {

        $model = new RegisterRequestModel();
        if (!$model->load(Yii::$app->request->bodyParams, '') || !$model->validate()) {
            $model->throwModelException($model->errors);
        }

        try {
            $response = Yii::$app->getResponse()->setStatusCode(201);
            return [
                'status'  => $response->statusCode,
                'message' => 'Registration completed successfully.',
                'data'    => $this->controller->service->register($model)
            ];
        } catch (\Exception $e) {
            throw new ServerErrorHttpException('Something is wrong, please try again later');
        }
    }
}

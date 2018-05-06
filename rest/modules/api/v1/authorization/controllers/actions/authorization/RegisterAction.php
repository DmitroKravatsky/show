<?php

namespace rest\modules\api\v1\authorization\controllers\actions\authorization;

use rest\modules\api\v1\authorization\controllers\AuthorizationController;
use rest\modules\api\v1\authorization\models\RestUserEntity;
use yii\rest\Action;

/**
 * Class RegisterAction
 * @package rest\modules\api\v1\authorization\controllers\actions\authorization
 */
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
     *         description = "success",
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
     *              "message": "Registration was successfully ended",
     *              "data": {
     *                  "id" : 21,
     *                  "phone_number": "+380939353498",
     *                   "status": "UNVERIFIED"
     *              }
     *         }
     *     ),
     *     @SWG\Response (
     *         response = 422,
     *         description = "Validation Error"
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
     * @throws \yii\web\ServerErrorHttpException
     * @throws \yii\web\UnprocessableEntityHttpException
     */
    public function run()
    {
        /** @var RestUserEntity $model */
        $model = new $this->modelClass;
        /** @var RestUserEntity $user */
        $user = $model->register(\Yii::$app->request->bodyParams);

        \Yii::$app->getResponse()->setStatusCode(200, 'Registration was successfully ended');
        return [
            'id'            => $user->id,
            'phone_number'  => $user->phone_number,
            'status'        => $user->status,
        ];
    }
}
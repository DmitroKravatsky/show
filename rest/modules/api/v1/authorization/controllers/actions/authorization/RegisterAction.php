<?php

namespace rest\modules\api\v1\authorization\controllers\actions\authorization;

use common\behaviors\ValidatePostParameters;
use rest\modules\api\v1\authorization\{ controllers\AuthorizationController, models\RestUserEntity };
use yii\rest\Action;
use yii\web\{ ErrorHandler, ServerErrorHttpException, UnprocessableEntityHttpException };
use Yii;

/**
 * Class RegisterAction
 * @package rest\modules\api\v1\authorization\controllers\actions\authorization
 * @mixin ValidatePostParameters
 */
class RegisterAction extends Action
{
    /** @var  AuthorizationController */
    public $controller;

    /**
     * @var array
     */
    public $params = [];

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'reportParams' => [
                'class'       => ValidatePostParameters::class,
                'inputParams' => ['phone_number', 'password', 'terms_condition', 'confirm_password',]
            ]
        ];
    }

    /**
     * @return bool
     * @throws \yii\base\InvalidConfigException
     * @throws \yii\web\BadRequestHttpException
     */
    protected function beforeRun(): bool
    {
        $this->validationParams();

        return parent::beforeRun();
    }

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
     *              "message": "Регистрация прошла успешно.",
     *              "data": {
     *                  "id" : 21,
     *                  "phone_number": "+380939353498",
     *                   "status": "UNVERIFIED"
     *              }
     *         }
     *     ),
     *     @SWG\Response (
     *         response = 400,
     *         description = "Bad Request"
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
     * @throws \yii\web\ServerErrorHttpException
     * @throws \yii\web\UnprocessableEntityHttpException
     */
    public function run()
    {
        /** @var RestUserEntity $model */
        $model = new $this->modelClass;
        try {
            /** @var RestUserEntity $user */
            $user = $model->register(Yii::$app->request->bodyParams);
        } catch (UnprocessableEntityHttpException $e) {
            throw new UnprocessableEntityHttpException($e->getMessage());
        } catch (\Exception $e) {
            Yii::error(ErrorHandler::convertExceptionToString($e));
            throw new ServerErrorHttpException('Something is wrong, please try again later');
        }

        $response = Yii::$app->getResponse()->setStatusCode(201);
        return [
            'status'  => $response->statusCode,
            'message' => 'Регистрация прошла успешно.',
            'data'    => [
                'id'            => $user->id,
                'phone_number'  => $user->phone_number,
                'status'        => $user->status,
            ],
        ];
    }
}
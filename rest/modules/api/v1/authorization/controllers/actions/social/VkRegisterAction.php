<?php

namespace rest\modules\api\v1\authorization\controllers\actions\social;

use common\behaviors\ValidatePostParameters;
use rest\modules\api\v1\authorization\controllers\SocialController;
use rest\modules\api\v1\authorization\models\RestUserEntity;
use yii\rest\Action;

/**
 * Class VkRegisterAction
 * @package rest\modules\api\v1\authorization\controllers\actions\social
 * @mixin ValidatePostParameters
 */
class VkRegisterAction extends Action
{
    /** @var  SocialController */
    public $controller;
    
    /**
     * @var array
     */
    public $params = [];

    /**
     * @return array
     */
    public function behaviors(): array
    {
        return [
            'reportParams' => [
                'class'       => ValidatePostParameters::className(),
                'inputParams' => [
                    'token', 'terms_condition'
                ]
            ],
        ];
    }

    /**
     * @return bool
     *
     * @throws \yii\web\BadRequestHttpException
     */
    protected function beforeRun(): bool
    {
        $this->validationParams();
        return parent::beforeRun();
    }

    /**
     * Vk register action
     *
     * @SWG\Post(path="/social/vk-register",
     *      tags={"Authorization module"},
     *      summary="User vk registration",
     *      description="User registration via vk",
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *          in = "formData",
     *          name = "token",
     *          description = "user's token on vk",
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
     *                  @SWG\Property(property="access_token", type="string", description="access token")
     *              ),
     *         ),
     *         examples = {
     *              "status": 201,
     *              "message": "Регистрация прошла успешно.",
     *              "data": {
     *                  "access_token": "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJqdGkiOjExLCJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiIsImV4cCI6MTUxODE3MjA2NX0.YpKRykzIfEJI5RhB5HYd5pDdBy8CWrA5OinJYGyVmew"
     *              }
     *         }
     *     ),
     *      @SWG\Response (
     *         response = 422,
     *         description = "Validation Error"
     *     ),
     *     @SWG\Response(
     *         response = 500,
     *         description = "Internal Server Error"
     *     )
     * )
     *
     * @return array
     * 
     * @throws \yii\web\BadRequestHttpException
     * @throws \yii\web\ServerErrorHttpException
     * @throws \yii\web\UnprocessableEntityHttpException
     */
    public function run(): array
    {
        /** @var RestUserEntity $model */
        $model = new $this->modelClass;
        $user = $model->vkRegister(\Yii::$app->request->bodyParams);

        return $this->controller->setResponse(
            201, 'Регистрация прошла успешно.', ['access_token' => $user->getJWT(['user_id' => $user->id])]
        );
    }
}
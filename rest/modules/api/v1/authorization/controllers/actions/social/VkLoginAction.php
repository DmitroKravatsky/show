<?php

namespace rest\modules\api\v1\authorization\controllers\actions\social;

use rest\modules\api\v1\authorization\controllers\SocialController;
use rest\modules\api\v1\authorization\models\RestUserEntity;
use yii\rest\Action;
use common\behaviors\ValidatePostParameters;

/**
 * Class VkLoginAction
 * @package rest\modules\api\v1\authorization\controllers\actions\social
 * @mixin ValidatePostParameters
 */
class VkLoginAction extends Action
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
                    'token',
                ]
            ],
        ];
    }

    /**
     * @return bool
     * @throws \yii\web\BadRequestHttpException
     */
    protected function beforeRun(): bool
    {
        $this->validationParams();
        return parent::beforeRun();
    }

    /**
     * @SWG\Post(path="/social/vk-login",
     *      tags={"Authorization module"},
     *      summary="User vk login",
     *      description="User login via vk",
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *          in = "formData",
     *          name = "token",
     *          description = "user's token on vk",
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
     *              @SWG\Property(property="data", type="object",
     *                  @SWG\Property(property="access_token", type="string", description="access token")
     *              ),
     *         ),
     *         examples = {
     *              "status": 200,
     *              "message": "Авторизация прошла успешно.",
     *              "data": {
     *                  "access_token": "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJqdGkiOjExLCJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiIsImV4cCI6MTUxODE3MjA2NX0.YpKRykzIfEJI5RhB5HYd5pDdBy8CWrA5OinJYGyVmew"
     *              }
     *         }
     *     ),
     *      @SWG\Response(
     *         response = 404,
     *         description = "User not found"
     *     ),
     *     @SWG\Response(
     *         response = 500,
     *         description = "Internal Server Error"
     *     )
     * )
     *
     * @return array
     * @throws \yii\web\NotFoundHttpException
     * @throws \yii\web\ServerErrorHttpException
     */
    public function run(): array 
    {
        /** @var RestUserEntity $model */
        $model = new $this->modelClass;
        $user = $model->vkLogin(\Yii::$app->request->post('token'));

        return $this->controller->setResponse(
            200, 'Авторизация прошла успешно.', ['access_token' => $user->getJWT(['user_id' => $user->id])]
        );
    }
}
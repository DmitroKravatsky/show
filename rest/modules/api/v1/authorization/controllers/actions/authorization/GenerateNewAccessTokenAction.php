<?php

namespace rest\modules\api\v1\authorization\controllers\actions\authorization;

use common\behaviors\ValidatePostParameters;
use rest\modules\api\v1\authorization\controllers\AuthorizationController;
use rest\modules\api\v1\authorization\models\RestUserEntity;
use yii\rest\Action;
use yii\web\HttpException;
use yii\web\NotFoundHttpException;
use yii\web\ServerErrorHttpException;

/**
 * Class GenerateNewAccessTokenAction
 * @package rest\modules\api\v1\authorization\controllers\actions\authorization
 *
 * @mixin ValidatePostParameters
 */
class GenerateNewAccessTokenAction extends Action
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
                'inputParams' => [
                    'refresh_token'
                ]
            ]
        ];
    }

    /**
     * @inheritdoc
     */
    public function beforeRun()
    {
        // todo данный метод должен подсвечиваться. Пример как это сделать http://joxi.ru/KAxedVDc4yKG6r
        $this->validationParams();

        return parent::beforeRun();
    }

    /**
     * GenerateNewAccessToken action
     *
     * @SWG\Post(path="/authorization/generate-new-access-token",
     *      tags={"Authorization module"},
     *      summary="Generate-new-access-token for user",
     *      description="Generate new access_token for user",
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *        in = "header",
     *        name = "Authorization",
     *        description = "Authorization: Bearer &lt;token&gt;",
     *        required = true,
     *        type = "string"
     *      ),
     *      @SWG\Parameter(
     *          in = "formData",
     *          name = "refresh_token",
     *          description = "User refresh_token",
     *          required = true,
     *          type = "string"
     *      ),
     *      @SWG\Response(
     *         response = 201,
     *         description = "created",
     *         @SWG\Schema(
     *              type="object",
     *              @SWG\Property(property="status", type="integer", description="Status code"),
     *              @SWG\Property(property="message", type="string", description="Status message"),
     *              @SWG\Property(property="data", type="object",
     *                  @SWG\Property(property="access_token",  type="string",  description="access token"),
     *                  @SWG\Property(property="refresh_token", type="string",  description="refresh token"),
     *                  @SWG\Property(property="exp",           type="int",     description="time of token expiration"),
     *                  @SWG\Property(property="data", type="object",
     *                      @SWG\Property(property="id",            type="integer", description="user id"),
     *                      @SWG\Property(property="phone_number",  type="string",  description="user's phone number"),
     *                      @SWG\Property(property="role",          type="string",  description="user's role"),
     *                      @SWG\Property(property="created_at",    type="string",  description="creation time"),
     *                      @SWG\Property(property="status",        type="string",  description="user profile status")
     *                  ),
     *              ),
     *         ),
     *         examples = {
     *              "status": 201,
     *              "message": "New access token has been generated",
     *              "data": {
     *                  "access_token": "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJqdGkiOjExLCJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiIsImV4cCI6MTUxODE3MjA2NX0.YpKRykzIfEJI5RhB5HYd5pDdBy8CWrA5OinJYGyVmew",
     *                  "refresh_token": "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9eyJqdGkiOjExLCJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiIsImV4c",
     *                  "exp": "1520070475",
     *                  "user": {
     *                      "id": "531",
     *                      "phone_number": "+380959751856",
     *                      "role": "guest",
     *                      "created_at": "1520070475",
     *                      "status": "VERIFIED"
     *                  }
     *              }
     *         }
     *     ),
     *     @SWG\Response(
     *         response = 400,
     *         description = "Parameter required"
     *     ),
     *     @SWG\Response(
     *         response = 404,
     *         description = "User not found"
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
     *
     * Generate New Access Token
     *
     * @return array
     * @throws HttpException
     * @throws NotFoundHttpException
     * @throws ServerErrorHttpException
     */
    public function run()
    {
        /** @var  $restUser RestUserEntity */
        $restUser = new $this->modelClass();
        $responseData = $restUser->generateNewAccessToken();

        \Yii::$app->getResponse()->setStatusCode(201, 'New token is created');
        return $responseData;
    }

}

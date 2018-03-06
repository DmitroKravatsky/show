<?php

namespace rest\modules\api\v1\authorization\controllers\actions\authorization;

use rest\behaviors\ResponseBehavior;
use rest\modules\api\v1\authorization\models\RestUserEntity;
use yii\rest\Action;
use yii\web\HttpException;
use yii\web\NotFoundHttpException;
use yii\web\ServerErrorHttpException;

/**
 * Class GenerateNewAccessTokenAction
 * @package rest\modules\api\v1\authorization\controllers\actions\authorization
 */
class GenerateNewAccessTokenAction extends Action
{

    /**
     * Register action
     *
     * @SWG\Post(path="/authorization/generate-new-access-token",
     *      tags={"Authorization module"},
     *      summary="User generate-new-access-token",
     *      description="User generate-new-access-token",
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *          in = "formData",
     *          name = "email",
     *          description = "User email",
     *          required = false,
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
     *                      @SWG\Property(property="id",            type="integer", description="id"),
     *                      @SWG\Property(property="email",         type="string",  description="user's email"),
     *                      @SWG\Property(property="role",          type="string",  description="user's role"),
     *                      @SWG\Property(property="created_at",    type="string",  description="creation time")
     *              ),
     *         ),
     *         examples = {
     *              "status": 201,
     *              "message": "Новый токен сгенерирован",
     *              "data": {
     *                  "access_token": "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJqdGkiOjExLCJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiIsImV4cCI6MTUxODE3MjA2NX0.YpKRykzIfEJI5RhB5HYd5pDdBy8CWrA5OinJYGyVmew"
     *                  "refresh_token": "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9eyJqdGkiOjExLCJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiIsImV4c"
     *                  "exp": "1520070475"
     *                      "user": {
     *                          "id": "531"
     *                          "email": "guest@gmail.com"
     *                          "role": "user"
     *                          "created_at": "1520070475"
     *                      }
     *              }
     *         }
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
    /**
     * Generate New Access Token action
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

        /** @var $result ResponseBehavior */
        $result = $this->controller;
        return $result->setResponse(201, 'New token is created', $responseData);
    }

}

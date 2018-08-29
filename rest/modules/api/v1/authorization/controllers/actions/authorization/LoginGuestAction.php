<?php

namespace rest\modules\api\v1\authorization\controllers\actions\authorization;

use rest\modules\api\v1\authorization\controllers\AuthorizationController;
use rest\modules\api\v1\authorization\models\RestUserEntity;
use yii\rest\Action;
use yii\web\ServerErrorHttpException;
use yii\web\UnauthorizedHttpException;

/**
 * Class LoginGuestAction
 * @package rest\modules\api\v1\authorization\controllers\actions\authorization
 */
class LoginGuestAction extends Action
{
    /** @var  AuthorizationController */
    public $controller;
    
    /** @var  RestUserEntity $modelClass */
    public $modelClass;

    /**
     * @SWG\Post(path="/authorization/login-guest",
     *      tags={"Authorization module"},
     *      summary="User guest login",
     *      description="User guest login",
     *      produces={"application/json"},
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
     *              "message": "Authorization was successful",
     *              "data": {
     *                  "access_token": "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJqdGkiOjExLCJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiIsImV4cCI6MTUxODE3MjA2NX0.YpKRykzIfEJI5RhB5HYd5pDdBy8CWrA5OinJYGyVmew"
     *              }
     *         }
     *     ),
     *     @SWG\Response (
     *         response = 500,
     *         description = "Internal Server Error"
     *     )
     * )
     * @return array
     *
     * @throws UnauthorizedHttpException
     * @throws ServerErrorHttpException
     */
    public function run(): array
    {
        try {
            $this->modelClass = new RestUserEntity();
            if ($user = $this->modelClass->loginGuest()) {
                \Yii::$app->getResponse()->setStatusCode(200, 'Authorization was successful');
                return [
                    'status'  => \Yii::$app->response->statusCode,
                    'message' => "Authorization was successful",
                    'data'    => [
                        'access_token' => $user->getJWT(['user_id' => $user->id])
                    ]
                ];
            }
        } catch (ServerErrorHttpException $e) {
            throw new ServerErrorHttpException('Internal server error');
        }
    }
}

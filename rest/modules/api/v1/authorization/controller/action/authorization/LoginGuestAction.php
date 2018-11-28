<?php

declare(strict_types=1);

namespace rest\modules\api\v1\authorization\controller\action\authorization;

use Yii;
use rest\modules\api\v1\authorization\controller\AuthorizationController;
use rest\modules\api\v1\authorization\entity\AuthUserEntity;
use yii\rest\Action;
use yii\web\{ ServerErrorHttpException, ErrorHandler };

class LoginGuestAction extends Action
{
    /** @var  AuthorizationController */
    public $controller;
    
    /** @var  AuthUserEntity $modelClass */
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
     *              "message": "Authorization was successfully ended",
     *              "data": {
     *                  "access_token": "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJqdGkiOjExLCJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiIsImV4cCI6MTUxODE3MjA2NX0.YpKRykzIfEJI5RhB5HYd5pDdBy8CWrA5OinJYGyVmew",
     *                  "exp": 1536224824,
     *              }
     *         }
     *     ),
     *     @SWG\Response (
     *         response = 500,
     *         description = "Server Error"
     *     )
     * )
     * @return array
     *
     * @throws ServerErrorHttpException
     */
    public function run(): array
    {
        try {
            return [
                'status'  => Yii::$app->getResponse()->getStatusCode(),
                'message' => 'Authorization was successfully ended.',
                'data'    => $this->controller->service->loginGuest()
            ];
        } catch (\Exception $e) {
            Yii::error(ErrorHandler::convertExceptionToString($e));
            throw new ServerErrorHttpException('Something is wrong, please try again later.');
        }
    }
}

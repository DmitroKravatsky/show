<?php

namespace rest\modules\api\v1\authorization\controllers\actions\authorization;

use common\models\userNotifications\UserNotificationsEntity;
use rest\modules\api\v1\authorization\controllers\AuthorizationController;
use rest\modules\api\v1\authorization\models\RestUserEntity;
use yii\rest\Action;
// todo
use yii\web\ServerErrorHttpException;
use yii\web\UnauthorizedHttpException;
 // todo

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
                return $this->controller->setResponse(
                    200,
                    'Authorization was successful',
                    [ 'access_token' => $user->getJWT(['user_id' => $user->id]) ]
                );
            }

        } catch (ServerErrorHttpException $e) {
            throw new ServerErrorHttpException('Internal server error');
        }
    }

    /**
     * @return mixed
     */
    protected function afterRun()
    {
        $userNotifications = new UserNotificationsEntity();
        return $userNotifications->addNotify(
            UserNotificationsEntity::getMessageForLoginGuest(),
            current($this->modelClass->findByRole(RestUserEntity::ROLE_GUEST))
        );
    }
}

<?php

namespace rest\modules\api\v1\authorization\controllers\actions\authorization;

use common\models\userNotifications\UserNotificationsEntity;
use rest\modules\api\v1\authorization\controllers\AuthorizationController;
use rest\modules\api\v1\authorization\models\RestUserEntity;
use yii\rest\Action;
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
     * @return array
     * @throws UnauthorizedHttpException
     */
    public function run(): array
    {
        $this->modelClass = new RestUserEntity();
        if ($user = $this->modelClass->loginGuest()) {
            return $this->controller->setResponse(
                200, 'Авторизация прошла успешно.', ['access_token' => $user->getJWT(['user_id' => $user->id])]);
        }
        
        throw new UnauthorizedHttpException('Ошибка авторизации.');
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
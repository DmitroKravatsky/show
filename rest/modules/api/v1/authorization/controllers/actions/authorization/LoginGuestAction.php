<?php

namespace rest\modules\api\v1\authorization\controllers\actions\authorization;

use common\models\userNotifications\UserNotificationsEntity;
use rest\modules\api\v1\authorization\models\RestUserEntity;
use yii\rest\Action;

/**
 * Class LoginGuestAction
 * @package rest\modules\api\v1\authorization\controllers\actions\authorization
 */
class LoginGuestAction extends Action
{
    /** @var  RestUserEntity $modelClass */
    public $modelClass;

    /**
     * @return mixed
     * @throws \yii\web\UnauthorizedHttpException
     */
    public function run()
    {
        $this->modelClass = new RestUserEntity();
        return  $this->modelClass->loginGuest();
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
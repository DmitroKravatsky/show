<?php

namespace rest\modules\api\v1\user\controllers\actions\notifications;

use common\models\userNotifications\UserNotificationsEntity;
use yii\data\ArrayDataProvider;
use yii\rest\Action;

/**
 * Class ListAction
 * @package rest\modules\api\v1\user\controllers\actions\notifications
 */
class ListAction extends Action
{
    /**
     * Returns list of user notifications
     * 
     * @SWG\Get(path="/user/user-notifications/list",
     *      tags={"User module"},
     *      summary="Get user profile",
     *      description="Get user profile",
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *        in = "header",
     *        name = "Authorization",
     *        description = "Authorization: Bearer &lt;token&gt;",
     *        required = true,
     *        type = "string"
     *      ),
     *      @SWG\Response(
     *         response = 200,
     *         description = "success"
     *     ),
     *     @SWG\Response (
     *         response = 401,
     *         description = "Invalid credentials or Expired token"
     *     )
     * )
     *
     * @return \yii\data\ArrayDataProvider
     */
    public function run(): ArrayDataProvider
    {
        /** @var UserNotificationsEntity $userNotifications */
        $userNotifications = new $this->modelClass;
        return $userNotifications->getUserNotificationsByUser(\Yii::$app->user->id);
    }
}
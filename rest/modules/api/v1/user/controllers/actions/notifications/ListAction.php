<?php

namespace rest\modules\api\v1\user\controllers\actions\notifications;

use common\models\userNotifications\UserNotifications;
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
     * @SWG\Get(path="/user/notifications/list",
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
     *      @SWG\Parameter(
     *        in = "formData",
     *        name = "per-page",
     *        description = "Amount of posts per page",
     *        required = false,
     *        type = "integer"
     *      ),
     *      @SWG\Parameter(
     *        in = "formData",
     *        name = "page",
     *        description = "next page",
     *        required = false,
     *        type = "integer"
     *      ),
     *      @SWG\Parameter(
     *        in = "formData",
     *        name = "read",
     *        description = "read status",
     *        required = false,
     *        type = "integer",
     *        enum = {0,1}
     *      ),
     *      @SWG\Response(
     *         response = 200,
     *         description = "success",
     *         @SWG\Schema(
     *              type="object",
     *              @SWG\Property(property="items",type="object",
     *                   @SWG\Property(property="id", type="integer", description="user_notification relation id"),
     *                   @SWG\Property(property="user_id", type="integer", description="user id"),
     *                   @SWG\Property(property="notification_id", type="integer", description="notification id"),
     *                   @SWG\Property(property="is_read", type="integer", description="read status"),
     *                   @SWG\Property(property="created_at", type="integer", description="created date"),
     *                   @SWG\Property(property="updated_at", type="integer", description="updated date"),
     *                   @SWG\Property(property="notification",type="object",
     *                      @SWG\Property(property="id", type="integer", description="notification id"),
     *                      @SWG\Property(property="type", type="string", description="notification type"),
     *                      @SWG\Property(property="custom_data", type="string", description="notification data"),
     *                      @SWG\Property(property="text", type="string", description="notification text"),
     *                      @SWG\Property(property="created_at", type="integer", description="created date"),
     *                      @SWG\Property(property="updated_at", type="integer", description="updated date"),
     *                  ),
     *              ),
     *              @SWG\Property(property="_links", type="object",
     *                  @SWG\Property(property="self", type="object",
     *                      @SWG\Property(property="href", type="string", description="Current link"),
     *                  ),
     *                  @SWG\Property(property="first", type="object",
     *                      @SWG\Property(property="href", type="string", description="First page link"),
     *                  ),
     *                  @SWG\Property(property="prev", type="object",
     *                      @SWG\Property(property="href", type="string", description="Prev page link"),
     *                  ),
     *                  @SWG\Property(property="next", type="object",
     *                      @SWG\Property(property="href", type="string", description="Next page link"),
     *                  ),
     *                  @SWG\Property(property="last", type="object",
     *                      @SWG\Property(property="href", type="string", description="Last page link"),
     *                  ),
     *             ),
     *             @SWG\Property(property="_meta", type="object",
     *                @SWG\Property(property="self", type="object",
     *                    @SWG\Property(property="total-count", type="string", description="Total number of items"),
     *                    @SWG\Property(property="page-count", type="integer", description="Current page"),
     *                    @SWG\Property(property="current-page", type="integer", description="Current page"),
     *                    @SWG\Property(property="per-page", type="integer", description="Number of items per page"),
     *                )
     *             ),
     *         ),
     *         examples = {
     *              "items": {
     *                  {
     *                     "id": 21,
     *                     "user_id": 193,
     *                     "notification_id": 93,
     *                     "is_read": 1,
     *                     "created_at": 1536667120,
     *                     "updated_at": 1536667120,
     *                     "notification": {
     *                         "id": 93,
     *                         "type": "new_bid",
     *                         "custom_data": "{full_name: Dmytro Krava, sum : 115, currency : usd, wallet : 918235401948147371623}",
     *                         "text": "User {full_name} has created new bid. Transfer to the card {sum} {currency} through the Wallet app. Recipient:Card/account {wallet}.",
     *                         "created_at": 1536667120,
     *                         "updated_at": 1536667120
     *                     }
     *                  },
     *              },
     *              "_links": {
     *                   "self": {
     *                   "href": "http://work.local/api/v1/user/notifications?per-page=2&page=2&read=0"
     *                   },
     *                   "first": {
     *                   "href": "http://work.local/api/v1/user/notifications?per-page=2&page&read=0"
     *                   },
     *                   "prev": {
     *                   "href": "http://work.local/api/v1/user/notifications?per-page=2&page&read=0"
     *                   }
     *               },
     *               "_meta": {
     *                   "totalCount": 4,
     *                   "pageCount": 2,
     *                   "currentPage": 2,
     *                   "perPage": 2
     *               }
     *         }
     *
     *     ),
     *     @SWG\Response (
     *         response = 401,
     *         description = "Invalid credentials or Expired token"
     *     ),
     *     @SWG\Response (
     *         response = 403,
     *         description = "Forbidden"
     *     ),
     *     @SWG\Response(
     *        response = 500,
     *        description = "Internal Server Error"
     *     )

     * )
     *
     * @return \yii\data\ArrayDataProvider
     */
    public function run(): ArrayDataProvider
    {
        /** @var UserNotifications $userNotifications */
        $userNotifications = new $this->modelClass;
        return $userNotifications->getUserNotificationsByUser(\Yii::$app->request->get());
    }
}

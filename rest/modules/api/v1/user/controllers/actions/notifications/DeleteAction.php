<?php

namespace rest\modules\api\v1\user\controllers\actions\notifications;

use common\models\userNotifications\UserNotifications;
use rest\modules\api\v1\user\controllers\UserNotificationsController;
use yii\rest\Action;
use yii\web\NotFoundHttpException;
use yii\web\ServerErrorHttpException;

/**
 * Class DeleteAction
 * @package rest\modules\api\v1\user\controllers\actions\notifications
 */
class DeleteAction extends Action
{
    /** @var  UserNotificationsController */
    public $controller;

    /**
     * Deletes an existing UserNotificationsEntity
     *
     * @SWG\Delete(path="/user/notifications/{id}",
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
     *        in = "path",
     *        name = "id",
     *        description = "Notification id",
     *        required = true,
     *        type = "integer"
     *      ),
     *      @SWG\Response(
     *         response = 200,
     *         description = "OK",
     *         @SWG\Schema(
     *              type="object",
     *              @SWG\Property(property="status", type="integer", description="Status code"),
     *              @SWG\Property(property="message", type="string", description="Status message"),
     *              @SWG\Property(property="data", type="object",
     *                  @SWG\Property(property="id", type="integer", description="Notification id")
     *              ),
     *         ),
     *         examples = {
     *              "status": 200,
     *              "message": "Notification was successfully deleted",
     *              "data": {
     *                  "id": 6
     *              }
     *         }
     *     ),
     *     @SWG\Response (
     *         response = 401,
     *         description = "Unauthorized"
     *     ),
     *     @SWG\Response (
     *         response = 404,
     *         description = "Not Found"
     *     ),
     *     @SWG\Response (
     *         response = 403,
     *         description = "Forbidden"
     *     ),
     *     @SWG\Response(
     *         response = 500,
     *         description = "Internal Server Error"
     *     )
     * )
     *
     * @param $id
     * @return array
     * @throws NotFoundHttpException
     * @throws ServerErrorHttpException
     */
    public function run($id): array
    {
        try {
            /** @var UserNotifications $userNotificationsModel */
            $userNotificationsModel = new $this->modelClass;
            if ($userNotificationsModel->deleteNotify($id, \Yii::$app->user->id)) {
                $response = \Yii::$app->getResponse()
                    ->setStatusCode(200, \Yii::t('app', 'Notification was successfully deleted'));
                return [
                    'status'  => $response->statusCode,
                    'message' => $response->statusText
                ];
            }
            throw new ServerErrorHttpException;
        } catch (NotFoundHttpException $e) {
            throw new NotFoundHttpException($e->getMessage());
        } catch (\Exception $e) {
            \Yii::error($e->getMessage());
            throw new ServerErrorHttpException(\Yii::t(
                'app',
                'Something is wrong, please try again later')
            );
        }
    }
}

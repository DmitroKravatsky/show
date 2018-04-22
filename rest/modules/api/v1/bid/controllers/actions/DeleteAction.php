<?php

namespace rest\modules\api\v1\bid\controllers\actions;

use common\models\bid\BidEntity;
use rest\modules\api\v1\bid\controllers\BidController;
use yii\rest\Action;
use yii\web\NotFoundHttpException;
use yii\web\ServerErrorHttpException;

/**
 * Class DeleteAction
 * @package rest\modules\api\v1\bid\controllers\actions
 */
class DeleteAction extends Action
{
    /** @var  BidController */
    public $controller;

    /**
     * @SWG\Delete(path="/bid/{id}",
     *      tags={"Bid module"},
     *      summary="Bid delete",
     *      description="Delete a user bid",
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
     *        description = "Bid id",
     *        required = true,
     *        type = "integer"
     *      ),
     *      @SWG\Response(
     *         response = 200,
     *         description = "success",
     *         @SWG\Schema(
     *              type="object",
     *              @SWG\Property(property="status", type="integer", description="Status code"),
     *              @SWG\Property(property="message", type="string", description="Status message"),
     *              @SWG\Property(property="data", type="object",
     *                  @SWG\Property(property="id", type="integer", description="User id")
     *              ),
     *         ),
     *         examples = {
     *              "status": 200,
     *              "message": "Заявка успешно удалёна.",
     *              "data": {
     *                  "id": 6
     *              }
     *         }
     *     ),
     *     @SWG\Response (
     *         response = 401,
     *         description = "Invalid credentials or Expired token"
     *     ),
     *     @SWG\Response (
     *         response = 404,
     *         description = "Bid not found"
     *     ),
     *     @SWG\Response(
     *         response = 500,
     *         description = "Internal Server Error"
     *     )
     * )
     *
     * Deletes an existing Bid model
     *
     * @param $id
     * @return array
     * @throws NotFoundHttpException
     * @throws ServerErrorHttpException
     */
    public function run($id): array 
    {
        try {
            /** @var BidEntity $bid */
            $bid = new BidEntity();
            if ($bid->deleteBid($id)) {
                \Yii::$app->getResponse()->setStatusCode(200, 'The bid was successfully deleted');
                return [
                    'status'  => \Yii::$app->response->statusCode,
                    'message' => "The bid was successfully deleted",
                    'data'    => ['id' => $id]
                ];
            }
            throw new ServerErrorHttpException(\Yii::t('app', 'Произошла ошибка при удалении заявки.'));
        } catch (NotFoundHttpException $e) {
            throw new NotFoundHttpException();
        } catch (\Exception $e) {
            \Yii::error($e->getMessage());
            throw new ServerErrorHttpException();
        }
    }
}
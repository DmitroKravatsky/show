<?php

namespace rest\modules\api\v1\review\controllers\actions;

use common\models\review\ReviewEntity;
use rest\modules\api\v1\review\controllers\ReviewController;
use yii\rest\Action;
use yii\web\NotFoundHttpException;
use yii\web\ServerErrorHttpException;
use Yii;

/**
 * Class DeleteAction
 * @package rest\modules\api\v1\review\controllers\actions
 */
class DeleteAction extends Action
{
    /** @var  ReviewController */
    public $controller;

    /**
     * Deletes an existing Review model
     *
     * @SWG\Delete(path="/review/{id}",
     *      tags={"Review module"},
     *      summary="Review delete",
     *      description="Deletes review",
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
     *        description = "Review id",
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
     *                  @SWG\Property(property="id", type="integer", description="Review id")
     *              ),
     *         ),
     *         examples = {
     *              "status": 200,
     *              "message": "Отзыв успешно удалён.",
     *              "data": {
     *                  "id": 6
     *              }
     *         }
     *     ),
     *     @SWG\Response (
     *         response = 404,
     *         description = "Review not found"
     *     ),
     *     @SWG\Response (
     *         response = 401,
     *         description = "Invalid credentials or Expired token"
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
            $reviewModel = new ReviewEntity();
            if ($reviewModel->deleteReview($id)) {
                return $this->controller->setResponse(200, 'Отзыв успешно удалён.', ['id' => $id]);
            }
            throw new ServerErrorHttpException(Yii::t('app', 'Произошла ошибка при удалении отзыва.'));
        } catch (NotFoundHttpException $e) {
            throw new NotFoundHttpException($e->getMessage());
        } catch (\Exception $e) {
            Yii::error($e->getMessage());
            throw new ServerErrorHttpException(Yii::t('app', 'Произошла ошибка при удалении отзыва.'));
        }
    }
}
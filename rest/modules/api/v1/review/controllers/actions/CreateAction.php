<?php

namespace rest\modules\api\v1\review\controllers\actions;

use common\models\review\ReviewEntity;
use rest\modules\api\v1\review\controllers\ReviewController;
use yii\rest\Action;
use yii\web\UnprocessableEntityHttpException;
use yii\web\ServerErrorHttpException;

/**
 * Class CreateAction
 * @package rest\modules\api\v1\review\controllers\actions
 */
class CreateAction extends Action
{
    /** @var  ReviewController */
    public $controller;

    /**
     * @SWG\Post(path="/review",
     *      tags={"Review module"},
     *      summary="Review create",
     *      description="Creates review",
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *        in = "header",
     *        name = "Authorization",
     *        description = "Authorization: Bearer &lt;token&gt;",
     *        required = true,
     *        type = "string"
     *      ),
     *      @SWG\Parameter(
     *          in = "formData",
     *          name = "text",
     *          description = "review's text",
     *          required = true,
     *          type = "string"
     *      ),
     *      @SWG\Response(
     *         response = 201,
     *         description = "success",
     *         @SWG\Schema(
     *              type="object",
     *              @SWG\Property(property="status", type="integer", description="Status code"),
     *              @SWG\Property(property="message", type="string", description="Status message"),
     *              @SWG\Property(property="data", type="object",
     *                  @SWG\Property(property="id", type="integer", description="Review id"),
     *                  @SWG\Property(property="text", type="string", description="Review text")
     *              ),
     *         ),
     *         examples = {
     *              "status": 201,
     *              "message": "Отзыв успешно добавлен.",
     *              "data": {
     *                  "id": 6,
     *                  "text": "Деньги пришли быстро и без проблем"
     *              }
     *         }
     *     ),
     *     @SWG\Response (
     *         response = 422,
     *         description = "Validation Error"
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
     * @return array
     * @throws ServerErrorHttpException
     * @throws UnprocessableEntityHttpException
     */
    public function run(): array
    {
        try {
            /** @var ReviewEntity $reviewModel */
            $reviewModel = new $this->modelClass;
            $reviewModel = $reviewModel->create(\Yii::$app->request->bodyParams);
            
            return $this->controller->setResponse(201, 'Отзыв успешно добавлен.', $reviewModel->getAttributes(['id', 'text']));
        } catch (UnprocessableEntityHttpException $e) {
            throw new UnprocessableEntityHttpException($e->getMessage());
        } catch (\Exception $e) {
            \Yii::error($e->getMessage());
            throw new ServerErrorHttpException(\Yii::t('app', 'Произошла ошибка при создании заявки.'));
        }
    }
}
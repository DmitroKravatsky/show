<?php

namespace rest\modules\api\v1\review\controllers\actions;

use common\models\review\ReviewEntity;
use rest\modules\api\v1\review\controllers\ReviewController;
use yii\rest\Action;
use yii\web\NotFoundHttpException;
use yii\web\ServerErrorHttpException;
use common\behaviors\AccessUserStatusBehavior;

/**
 * Class UpdateAction
 * @package rest\modules\api\v1\review\controllers\actions
 * @mixin AccessUserStatusBehavior
 */
class UpdateAction extends Action
{
    /** @var  ReviewController */
    public $controller;

    /**
     * @return array
     */
    public function behaviors()
    {
        return [
            [
                'class'   => AccessUserStatusBehavior::class,
                'message' => 'Доступ запрещён.'
            ]
        ];
    }

    /**
     * @inheritdoc
     */
    protected function beforeRun()
    {
        $this->checkUserRole();
        return parent::beforeRun();
    }

    /**
     * Updates an existing Review model
     *
     * @SWG\Put(path="/review/{id}",
     *      tags={"Review module"},
     *      summary="Review update",
     *      description="Updates review",
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
     *      @SWG\Parameter(
     *          in = "formData",
     *          name = "text",
     *          description = "review's text",
     *          required = false,
     *          type = "string"
     *      ),
     *      @SWG\Response(
     *         response = 200,
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
     *              "status": 200,
     *              "message": "Отзыв успешно изменён.",
     *              "data": {
     *                  "id": 6,
     *                  "text": "Деньги пришли быстро и без проблем"
     *              }
     *         }
     *     ),
     *     @SWG\Response (
     *         response = 404,
     *         description = "Review not found"
     *     ),
     *     @SWG\Response (
     *         response = 422,
     *         description = "Validation Error"
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
     *         response = 500,
     *         description = "Internal Server Error"
     *     )
     * )
     * 
     * @param $id
     *
     * @return array
     *
     * @throws NotFoundHttpException
     * @throws ServerErrorHttpException
     */
    public function run($id)
    {
        try {
            /** @var ReviewEntity $reviewModel */
            $reviewModel = new $this->modelClass;
            $reviewModel = $reviewModel->updateReview($id, \Yii::$app->request->bodyParams);
            $response = \Yii::$app->getResponse()->setStatusCode(200, \Yii::t('app', 'Review was successfully edited'));
            return [
                'status'  => $response->statusCode,
                'message' => $response->statusText,
                'data'    => $reviewModel->getAttributes(['id', 'text'])
            ];
        } catch (NotFoundHttpException $e) {
            throw new NotFoundHttpException($e->getMessage());
        } catch (\Exception $e) {
            \Yii::error($e->getMessage());
            throw new ServerErrorHttpException(\Yii::t('app', 'Error on review update'));
        }
    }
}

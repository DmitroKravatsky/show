<?php

namespace rest\modules\api\v1\review\controllers\actions;

use Yii;
use yii\rest\Action;
use yii\web\{ NotFoundHttpException, ServerErrorHttpException };
use common\models\review\ReviewEntity;
use common\behaviors\AccessUserStatusBehavior;
use rest\modules\api\v1\review\controllers\ReviewController;

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
                'message' => 'Access Denied.'
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
     *          required = true,
     *          type = "string"
     *      ),
     *      @SWG\Response(
     *         response = 200,
     *         description = "OK",
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
     *              "message": "Review was successfully edited.",
     *              "data": {
     *                  "id": 6,
     *                  "text": "Деньги пришли быстро и без проблем"
     *              }
     *         }
     *     ),
     *     @SWG\Response (
     *         response = 401,
     *         description = "Unauthorized"
     *     ),
     *     @SWG\Response (
     *         response = 403,
     *         description = "Forbidden"
     *     ),
     *     @SWG\Response (
     *         response = 404,
     *         description = "Not Found"
     *     ),
     *     @SWG\Response (
     *         response = 422,
     *         description = "Unprocessable Entity"
     *     ),
     *     @SWG\Response(
     *         response = 500,
     *         description = "Server Error"
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
            $reviewModel = $reviewModel->updateReview($id, Yii::$app->request->bodyParams);

            return [
                'status'  => Yii::$app->getResponse()->getStatusCode(),
                'message' => 'Review was successfully edited.',
                'data'    => $reviewModel->getAttributes(['id', 'text'])
            ];
        } catch (NotFoundHttpException $e) {
            throw new NotFoundHttpException($e->getMessage());
        } catch (\Exception $e) {
            Yii::error($e->getMessage());
            throw new ServerErrorHttpException('Something wrong, please try again later.');
        }
    }
}

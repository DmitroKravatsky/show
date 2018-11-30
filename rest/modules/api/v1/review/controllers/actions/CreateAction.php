<?php

namespace rest\modules\api\v1\review\controllers\actions;

use common\models\review\ReviewEntity;
use rest\modules\api\v1\review\controllers\ReviewController;
use yii\rest\Action;
use yii\web\{ ForbiddenHttpException, UnprocessableEntityHttpException, ServerErrorHttpException };
use Yii;
use common\behaviors\AccessUserStatusBehavior;

/**
 * Class CreateAction
 * @package rest\modules\api\v1\review\controllers\actions
 * @mixin AccessUserStatusBehavior
 */
class CreateAction extends Action
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
                'message' => 'Access denied'
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
     *      @SWG\Parameter(
     *          in = "formData",
     *          name = "terms_condition",
     *          description = "Terms condition",
     *          required = true,
     *          type = "integer",
     *          enum = {0, 1}
     *      ),
     *      @SWG\Response(
     *         response = 201,
     *         description = "created",
     *         @SWG\Schema(
     *              type="object",
     *              @SWG\Property(property="status", type="integer", description="Status code"),
     *              @SWG\Property(property="message", type="string", description="Status message"),
     *              @SWG\Property(property="data", type="object",
     *                  @SWG\Property(property="id", type="integer", description="Review id"),
     *                  @SWG\Property(property="text", type="string", description="Review text"),
     *                  @SWG\Property(property="name", type="string", description="Review creator name")
     *              ),
     *         ),
     *         examples = {
     *              "status": 201,
     *              "message": "Review was successfully created",
     *              "data": {
     *                  "id": 6,
     *                  "text": "Деньги пришли быстро и без проблем",
     *                  "name": "Petr"
     *              }
     *         }
     *     ),
     *     @SWG\Response (
     *         response = 422,
     *         description = "Unprocessable Entity"
     *     ),
     *     @SWG\Response (
     *         response = 401,
     *         description = "Unauthorized"
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
     * @return mixed
     *
     * @throws ServerErrorHttpException
     * @throws UnprocessableEntityHttpException
     * @throws ForbiddenHttpException
     */
    public function run()
    {
        try {
            /** @var ReviewEntity $reviewModel */
            $reviewModel = new $this->modelClass;
            $review = $reviewModel->create(Yii::$app->request->bodyParams);

            $response = Yii::$app->getResponse()->setStatusCode(201);
            $response->data = [
                'status'  => $response->statusCode,
                'message' => 'Review was successfully created',
                'data'    => $review
            ];
        } catch (UnprocessableEntityHttpException $e) {
            throw new UnprocessableEntityHttpException($e->getMessage());
        } catch (ForbiddenHttpException $e) {
            throw new ForbiddenHttpException($e->getMessage());
        } catch (\Exception $e) {
            Yii::error($e->getMessage());
            throw new ServerErrorHttpException('Something is wrong, please try again later');
        }
    }
}

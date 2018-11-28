<?php

namespace rest\modules\api\v1\review\controllers\actions;

use Yii;
use common\models\review\ReviewEntity;
use yii\rest\Action;
use yii\web\ServerErrorHttpException;

/**
 * Class ListAction
 * @package rest\modules\api\v1\review\controllers\actions
 */
class ListAction extends Action
{
    /**
     * Returns reviews list
     *
     * @SWG\Get(path="/review",
     *      tags={"Review module"},
     *      summary="Review create",
     *      description="Creates review",
     *      produces={"application/json"},
     *      @SWG\Response(
     *         response = 200,
     *         description = "OK",
     *         @SWG\Schema(
     *              type="object",
     *              @SWG\Property(property="items", type="object",
     *                   @SWG\Property(property="id", type="integer", description="Review id"),
     *                   @SWG\Property(property="text", type="string", description="Review text"),
     *                   @SWG\Property(property="created_at", type="date", description="Review creation date"),
     *              ),
     *              @SWG\Property(property="_links", type="object",
     *                  @SWG\Property(property="self", type="object",
     *                      @SWG\Property(property="href", type="string", description="Current link"),
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
     *                 {
     *                   "id": 6,
     *                   "name":"Sasha",
     *                   "text": "Деньги пришли быстро и без проблем",
     *                   "created_at": 1520256641
     *                 },
     *                 {
     *                   "id": 8,
     *                   "name":"Dimon",
     *                   "text": "Деньги пришли быстро и без проблем.Everything OK",
     *                   "created_at": 1520256641
     *                  },
     *              },
     *              "_links": {
     *                   "self": {
     *                      "href": "http://work.local/api/v1/review/list"
     *                   },
     *               },
     *               "_meta": {
     *                   "totalCount": 4,
     *                   "pageCount": 2,
     *                   "currentPage": 2,
     *                   "perPage": 2
     *               }
     *         }
     *     ),
     *     @SWG\Response (
     *         response = 500,
     *         description = "Internal Server Error"
     *     )
     * )
     * @return \yii\data\ArrayDataProvider
     * @throws ServerErrorHttpException
     */
    public function run()
    {
        try {
            $reviewModel = new ReviewEntity();
            return $reviewModel->listReviews(Yii::$app->requestedParams);
        } catch (\Exception $e) {
            Yii::error($e->getMessage());
            throw new ServerErrorHttpException('Something is wrong, please try again later');
        }
    }
}

<?php

namespace rest\modules\api\v1\review\controllers\actions;

use common\models\review\ReviewEntity;
use yii\rest\Action;

/**
 * Class ListAction
 * @package rest\modules\api\v1\review\controllers\actions
 */
class ListAction extends Action
{
    /**
     * Returns reviews list
     *
     * @SWG\Get(path="/review/list",
     *      tags={"Review module"},
     *      summary="Review create",
     *      description="Creates review",
     *      produces={"application/json"},
     *      @SWG\Response(
     *         response = 200,
     *         description = "success",
     *         @SWG\Schema(
     *              type="object",
     *              @SWG\Property(property="status", type="integer", description="Status code"),
     *              @SWG\Property(property="message", type="string", description="Status message"),
     *              @SWG\Property(property="data", type="object",
     *                  @SWG\Property(property="id", type="integer", description="Review id"),
     *                  @SWG\Property(property="text", type="string", description="Review text"),
     *                  @SWG\Property(property="created_at", type="integer", description="Review created at")
     *              ),
     *         ),
     *         examples = {
     *              "status": 200,
     *              "message": "",
     *              "data": {
     *                  "id": 6,
     *                  "text": "Деньги пришли быстро и без проблем",
     *                  "created_at": "1520256641"
     *              }
     *         }
     *     )
     * )
     *
     * @return \yii\data\ArrayDataProvider
     */
    public function run()
    {
        $reviewModel = new ReviewEntity();
        return $reviewModel->listReviews(\Yii::$app->requestedParams);
    }
}
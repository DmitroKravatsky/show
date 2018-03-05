<?php

namespace rest\modules\api\v1\reserve\controllers\actions;

use common\models\reserve\ReserveEntity;
use Yii;

/**
 * Class ListAction
 * @package rest\api\v1\reserve\controllers\actions
 */
class ListAction extends \yii\rest\Action
{
    /**
     * @SWG\Get(path="/reserve/reserve/list",
     *      tags={"Reserve module"},
     *      summary="Reserve list",
     *      description="Reserves list",
     *      produces={"application/json"},
     *      @SWG\Response(
     *         response = 200,
     *         description = "success",
     *         @SWG\Schema(
     *              type="object",
     *              @SWG\Property(property="status", type="integer", description="Status code"),
     *              @SWG\Property(property="message", type="string", description="Status message"),
     *              @SWG\Property(property="data", type="object",
     *                  @SWG\Property(property="id", type="integer", description="User id"),
     *                  @SWG\Property(property="payment_system", type="string", description="payment system"),
     *                  @SWG\Property(property="currency", type="string", description="currency"),
     *                  @SWG\Property(property="sum", type="integer", description="sum"),
     *                  @SWG\Property(property="created_at", type="integer", description="created at"),
     *                  @SWG\Property(property="updated_at", type="integer", description="updated at")
     *              ),
     *         ),
     *         examples = {
     *              "status": 200,
     *              "data": {
     *                  "id": 4,
     *                  "payment_system": "yandex_money",
     *                  "currency": "rub",
     *                  "sum": "10000",
     *                  "created_at": 1520252768,
     *                  "updated_at": 1520252768
     *              }
     *         }
     *     )
     * )
     *
     * @return array
     */
    public function run(): array
    {
        /** @var ReserveEntity $reserve */
        $reserve = new $this->modelClass;

        return $reserve->find()->all();
    }
}
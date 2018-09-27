<?php

namespace rest\modules\api\v1\reserve\controllers\actions;

use common\models\reserve\ReserveEntity;
use yii\data\ArrayDataProvider;
use yii\rest\Action;
use Yii;

/**
 * Class ListAction
 * @package rest\api\v1\reserve\controllers\actions
 */
class ListAction extends Action
{
    /**
     * Get list reserves
     *
     * @SWG\Get(path="/reserve",
     *      tags={"Reserve module"},
     *      summary="Reserve list",
     *      description="Reserves list",
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *        in = "query",
     *        name = "per-page",
     *        description = "number of items per page",
     *        required = false,
     *        type = "integer"
     *      ),
     *      @SWG\Parameter(
     *        in = "query",
     *        name = "page",
     *        description = "the zero-based current page number",
     *        required = false,
     *        type = "integer"
     *      ),
     *     @SWG\Parameter(
     *        in = "query",
     *        name = "filter",
     *        description = "filter by currencies",
     *        required = false,
     *        type = "string",
     *        enum = {"uah", "rub", "usd"},
     *      ),
     *      @SWG\Response(
     *         response = 200,
     *         description = "success",
     *         @SWG\Schema(
     *              type="object",
     *              @SWG\Property(property="status", type="integer", description="Status code"),
     *              @SWG\Property(property="message", type="string", description="Status message"),
     *              @SWG\Property(property="data", type="object",
     *                  @SWG\Property(property="id", type="integer", description="Reserve id"),
     *                  @SWG\Property(property="payment_system", type="string", description="payment system"),
     *                  @SWG\Property(property="currency", type="string", description="currency"),
     *                  @SWG\Property(property="sum", type="integer", description="sum")
     *              ),
     *         ),
     *         examples = {
     *              "items": {
     *                  {
     *                      "id": 1,
     *                      "payment_system": "yandex_money",
     *                      "payment_system_id": 3,
     *                      "currency": "RUB",
     *                      "sum": 1234.23
     *                  },
     *                  {
     *                      "id": 2,
     *                      "payment_system": "privat24",
     *                      "payment_system_id": 4,
     *                      "currency": "UAH",
     *                      "sum": 23456.7
     *                  },
     *                  {
     *                      "id": 3,
     *                      "payment_system": "web_money",
     *                      "payment_system_id": 5,
     *                      "currency": "USD",
     *                      "sum": 2455
     *                  },
     *              },
     *              "_links": {
     *                   "self": {
     *                      "href": "http://dev.ratkus.biz.ua/api/v1/reserve?page=1&per-page=5"
     *                   },
     *                   "next": {
     *                     "href": "http://dev.ratkus.biz.ua/api/v1/reserve?per-page=1&page=2"
     *                   },
     *                   "last": {
     *                     "href": "http://dev.ratkus.biz.ua/api/v1/reserve?per-page=1&page=3"
     *                   }
     *               },
     *               "_meta": {
     *                   "totalCount": 4,
     *                   "pageCount": 2,
     *                   "currentPage": 2,
     *                   "perPage": 2
     *               }
     *         }
     *     )
     * )
     *
     * @return ArrayDataProvider
     */
    public function run()
    {
        /** @var ReserveEntity $reserve */
        $reserve = new $this->modelClass;
        return $reserve->getList(Yii::$app->request->queryParams);
    }
}
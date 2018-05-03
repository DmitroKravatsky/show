<?php

namespace rest\modules\api\v1\bid\controllers\actions;

use common\models\bid\BidEntity;
use rest\modules\api\v1\bid\controllers\BidController;
use yii\rest\Action;

/**
 * Class ListAction
 * @package rest\modules\api\v1\bid\controllers\actions
 */
class ListAction extends Action
{
    /**
     * @SWG\Get(path="/bid/list",
     *      tags={"Bid module"},
     *      summary="Bids list",
     *      description="Get user bids",
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *        in = "header",
     *        name = "Authorization",
     *        description = "Authorization: Bearer &lt;token&gt;",
     *        required = true,
     *        type = "string"
     *      ),
     *      @SWG\Parameter(
     *        in = "query",
     *        name = "per-page",
     *        description = "Amount of posts per page",
     *        required = false,
     *        type = "integer"
     *      ),
     *      @SWG\Parameter(
     *        in = "query",
     *        name = "page",
     *        description = "next page",
     *        required = false,
     *        type = "integer"
     *      ),
     *     @SWG\Parameter(
     *        in = "query",
     *        name = "sort",
     *        description = "field fo time sort",
     *        required = false,
     *        type = "string",
     *        enum = {"week", "month", "archive"},
     *      ),
     *      @SWG\Response(
     *         response = 200,
     *         description = "success",
     *         @SWG\Schema(
     *              type="object",
     *              @SWG\Property(property="items", type="object",
     *                   @SWG\Property(property="id", type="integer", description="Bid id"),
     *                   @SWG\Property(property="status", type="string", description="Bid status"),
     *                   @SWG\Property(property="from_payment_system", type="string", description="from payment system"),
     *                   @SWG\Property(property="to_payment_system", type="string", description="to payment system"),
     *                   @SWG\Property(property="from_currency", type="string", description="from currency"),
     *                   @SWG\Property(property="to_currency", type="string", description="to currency"),
     *                   @SWG\Property(property="from_sum", type="integer", description="from sum"),
     *                   @SWG\Property(property="to_sum", type="integer", description="to sum"),
     *              ),
     *              @SWG\Property(property="_links", type="object",
     *                  @SWG\Property(property="self", type="object",
     *                      @SWG\Property(property="href", type="string", description="Current link"),
     *                  ),
     *                  @SWG\Property(property="first", type="object",
     *                      @SWG\Property(property="href", type="string", description="First page link"),
     *                  ),
     *                  @SWG\Property(property="prev", type="object",
     *                      @SWG\Property(property="href", type="string", description="Prev page link"),
     *                  ),
     *                  @SWG\Property(property="next", type="object",
     *                      @SWG\Property(property="href", type="string", description="Next page link"),
     *                  ),
     *                  @SWG\Property(property="last", type="object",
     *                      @SWG\Property(property="href", type="string", description="Last page link"),
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
     *                  {
     *                      "id": 12,
     *                      "status": "accepted",
     *                      "from_payment_system": "web_money",
     *                      "to_payment_system": "privat24",
     *                      "from_currency": "uah",
     *                      "to_currency": "eur",
     *                      "from_sum": 150,
     *                      "to_sum": 1.5,
     *                  },
     *                  {
     *                      "id": 12,
     *                      "status": "accepted",
     *                      "from_payment_system": "privat24",
     *                      "to_payment_system": "yandex_money",
     *                      "from_currency": "usd",
     *                      "to_currency": "usd",
     *                      "from_sum": 2142,
     *                      "to_sum": 123124,
     *                  }
     *              },
     *              "_links": {
     *                   "self": {
     *                   "href": "http://work.local/api/v1/bid/list?per-page=2&page=2&sort=week"
     *                   },
     *                   "first": {
     *                   "href": "http://work.local/api/v1/bid/list?per-page=2&page=1&sort=week"
     *                   },
     *                   "prev": {
     *                   "href": "http://work.local/api/v1/bid/list?per-page=2&page=1&sort=week"
     *                   }
     *               },
     *               "_meta": {
     *                   "totalCount": 4,
     *                   "pageCount": 2,
     *                   "currentPage": 2,
     *                   "perPage": 2
     *               }
     *         }
     *
     *     ),
     *     @SWG\Response (
     *        response = 401,
     *        description = "Invalid credentials or Expired token"
     *     ),
     *     @SWG\Response(
     *        response = 500,
     *        description = "Internal Server Error"
     *     )
     * )
     *
     * @return \yii\data\ArrayDataProvider
     */
    public function run()
    {
        /** @var BidEntity $bid */
        $bid = new $this->modelClass;

        return $bid->getBids(\Yii::$app->request->queryParams);
    }
}
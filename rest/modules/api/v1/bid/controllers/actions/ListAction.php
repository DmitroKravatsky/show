<?php

namespace rest\modules\api\v1\bid\controllers\actions;

use common\models\bid\BidEntity;
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
     *        in = "formData",
     *        name = "per-page",
     *        description = "Amount of posts per page",
     *        required = false,
     *        type = "integer"
     *      ),
     *      @SWG\Parameter(
     *        in = "formData",
     *        name = "page",
     *        description = "next page",
     *        required = false,
     *        type = "integer"
     *      ),
     *      @SWG\Response(
     *         response = 200,
     *         description = "success",
     *         @SWG\Schema(
     *              type="object",
     *              @SWG\Property(property="items", type="object",
     *                   @SWG\Property(property="id", type="integer", description="User id"),
     *                   @SWG\Property(property="created_by", type="integer", description="Author id"),
     *                   @SWG\Property(property="name", type="string", description="User name"),
     *                   @SWG\Property(property="last_name", type="string", description="User last name"),
     *                   @SWG\Property(property="phone_number", type="string", description="User phone number"),
     *                   @SWG\Property(property="email", type="string", description="User email"),
     *                   @SWG\Property(property="status", type="string", description="Bid status"),
     *                   @SWG\Property(property="from_payment_system", type="string", description="from payment system"),
     *                   @SWG\Property(property="to_payment_system", type="string", description="to payment system"),
     *                   @SWG\Property(property="from wallet", type="string", description="from wallet"),
     *                   @SWG\Property(property="to wallet", type="string", description="to wallet"),
     *                   @SWG\Property(property="from_currency", type="string", description="from currency"),
     *                   @SWG\Property(property="to_currency", type="string", description="to currency"),
     *                   @SWG\Property(property="from_sum", type="integer", description="from sum"),
     *                   @SWG\Property(property="to_sum", type="integer", description="to sum"),
     *                   @SWG\Property(property="created_at", type="integer", description="created at"),
     *                   @SWG\Property(property="updated_at", type="integer", description="updated at")
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
     *                      "id": 4,
     *                      "created_by": 22,
     *                      "name": "Ivan",
     *                      "last_name": "Petrov",
     *                      "phone_number": "0939757501",
     *                      "email": "krarwa@gmail.com",
     *                      "status": "accepted",
     *                      "from_payment_system": "web_money",
     *                      "to_payment_system": "privat24",
     *                      "from_wallet": "153162262",
     *                      "to_wallet": "5649264646",
     *                      "from_currency": "uah",
     *                      "to_currency": "eur",
     *                      "from_sum": 150,
     *                      "to_sum": 1.5,
     *                      "created_at": 1231232321,
     *                      "updated_at": 1312323121
     *                  },
     *                  {
     *                      "id": 3,
     *                      "created_by": 22,
     *                      "name": "Ivan",
     *                      "last_name": "Petrov",
     *                      "phone_number": "0939757501",
     *                      "email": "krarwa@gmail.com",
     *                      "status": "accepted",
     *                      "from_payment_system": "privat24",
     *                      "to_payment_system": "yandex_money",
     *                      "from_wallet": "2wqas212ewqaf2f221rq",
     *                      "to_wallet": "wqdwqwqr34124251wqfdg4",
     *                      "from_currency": "usd",
     *                      "to_currency": "usd",
     *                      "from_sum": 2142,
     *                      "to_sum": 123124,
     *                      "created_at": 12242556,
     *                      "updated_at": 12425326
     *                  }
     *              },
     *              "_links": {
     *                   "self": {
     *                   "href": "http://work.local/api/v1/bid/list?per-page=2&page=2"
     *                   },
     *                   "first": {
     *                   "href": "http://work.local/api/v1/bid/list?per-page=2&page=1"
     *                   },
     *                   "prev": {
     *                   "href": "http://work.local/api/v1/bid/list?per-page=2&page=1"
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
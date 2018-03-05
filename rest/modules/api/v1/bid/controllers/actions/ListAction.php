<?php

namespace rest\modules\api\v1\bid\controllers\actions;

use common\models\bid\BidEntity;

/**
 * Class ListAction
 * @package rest\modules\api\v1\bid\controllers\actions
 */
class ListAction extends \yii\rest\Action
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
     *      @SWG\Response(
     *         response = 200,
     *         description = "success",
     *         @SWG\Schema(
     *              type="object",
     *              @SWG\Property(property="status", type="integer", description="Status code"),
     *              @SWG\Property(property="data", type="object",
     *                  @SWG\Property(property="id", type="integer", description="User id"),
     *                  @SWG\Property(property="created_by", type="integer", description="Author id"),
     *                  @SWG\Property(property="name", type="string", description="User name"),
     *                  @SWG\Property(property="last_name", type="string", description="User last name"),
     *                  @SWG\Property(property="phone_number", type="string", description="User phone number"),
     *                  @SWG\Property(property="email", type="string", description="User email"),
     *                  @SWG\Property(property="status", type="string", description="Bid status"),
     *                  @SWG\Property(property="from_payment_system", type="string", description="from payment system"),
     *                  @SWG\Property(property="to_payment_system", type="string", description="to payment system"),
     *                  @SWG\Property(property="from wallet", type="string", description="from wallet"),
     *                  @SWG\Property(property="to wallet", type="string", description="to wallet"),
     *                  @SWG\Property(property="from_currency", type="string", description="from currency"),
     *                  @SWG\Property(property="to_currency", type="string", description="to currency"),
     *                  @SWG\Property(property="from_sum", type="integer", description="from sum"),
     *                  @SWG\Property(property="to_sum", type="integer", description="to sum"),
     *                  @SWG\Property(property="created_at", type="integer", description="created at"),
     *                  @SWG\Property(property="updated_at", type="integer", description="updated at")
     *              ),
     *         ),
     *         examples = {
     *              "status": 200,
     *              "data": {
     *                  "id": 6,
     *                  "created_by": 5,
     *                  "name": "John",
     *                  "last_name": "Smith",
     *                  "phone_number": "+79787979879",
     *                  "email": "smith@gmail.com",
     *                  "status": "accepted",
     *                  "from_payment_system": "yandex_money",
     *                  "to_payment_system": "privat24",
     *                  "from_wallet": "1234123412341234",
     *                  "to_wallet": "1234123412341234",
     *                  "from_currency": "rub",
     *                  "to_currency": "usd",
     *                  "from_sum": "123",
     *                  "to_sum": "123.5",
     *                  "created_at": 1520246365,
     *                  "updated_at": 1520246365
     *              }
     *         }
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
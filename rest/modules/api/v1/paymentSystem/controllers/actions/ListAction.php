<?php

namespace rest\modules\api\v1\paymentSystem\controllers\actions;

use common\models\paymentSystem\PaymentSystem;
use yii\rest\Action;
use Yii;

class ListAction extends Action
{
    /**
     * @SWG\Get(path="/payment-system",
     *      tags={"Payment System module"},
     *      summary="Payment systems list",
     *      description="Get payment systems",
     *      produces={"application/json"},
     *     @SWG\Parameter(
     *        in = "query",
     *        name = "filter",
     *        description = "filter by currencies",
     *        required = false,
     *        type = "string",
     *        enum = {"uah", "rub", "usd", "eur", "wmx"},
     *      ),
     *      @SWG\Response(
     *         response = 200,
     *         description = "success",
     *         @SWG\Schema(
     *              type="object",
     *              @SWG\Property(property="items", type="object",
     *                   @SWG\Property(property="id", type="integer", description="Payment System id"),
     *                   @SWG\Property(property="name", type="string", description="Payment System name"),
     *                   @SWG\Property(property="currency", type="string", description="Payment System currency"),
     *                   @SWG\Property(property="payment_system_type", type="string", description="Payment System Type"),
     *                   @SWG\Property(property="min_transaction_sum", type="integer", description="Minimum Transaction Amount"),
     *              ),
     *         ),
     *         examples = {
     *             {
     *                 "id": 2,
     *                 "name": "Webmoney RUB",
     *                 "currency": "rub",
     *                 "payment_system_type": "online_wallet",
     *                 "min_transaction_sum": 200
     *             },
     *             {
     *                 "id": 3,
     *                 "name": "ВТБ 24 RUB",
     *                 "currency": "rub",
     *                 "payment_system_type": "credit_card",
     *                 "min_transaction_sum": 300
     *             },
     *             {
     *                 "id": 4,
     *                 "name": "Приват24 UAH",
     *                 "currency": "uah",
     *                 "payment_system_type": "credit_card",
     *                 "min_transaction_sum": 100
     *             }
     *         }
     *     ),
     * )
     *
     * @return \yii\data\ArrayDataProvider
     */
    public function run()
    {
        /** @var PaymentSystem $paymentSystem */
        $paymentSystem = $this->modelClass;
        return $paymentSystem::getList(Yii::$app->request->queryParams, false);
    }
}

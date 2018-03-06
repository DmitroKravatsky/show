<?php

namespace rest\modules\api\v1\bid\controllers\actions;

use common\behaviors\ValidateGetParameters;
use yii\rest\Action;

/**
 * Class DetailAction
 * @mixin ValidateGetParameters
 * @package rest\modules\api\v1\bid\controllers\actions
 */
class DetailAction extends Action
{
    /**
     * @var array
     */
    public $params = [];

    /**
     * @return array
     */
    public function behaviors(): array
    {
        return [
            'reportParams' => [
                'class'       => ValidateGetParameters::className(),
                'inputParams' => ['id']
            ],
        ];
    }

    /**
     * @return bool
     * @throws \yii\web\BadRequestHttpException
     */
    protected function beforeRun(): bool
    {
        $this->validationParams();
        return parent::beforeRun();
    }

    /**
     * @SWG\Get(path="/bid/detail?id={id}",
     *      tags={"Bid module"},
     *      summary="Bid detail",
     *      description="Get user bid details",
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
     *        description = "Bid id",
     *        required = true,
     *        type = "integer"
     *      ),
     *      @SWG\Response(
     *         response = 200,
     *         description = "success",
     *         @SWG\Schema(
     *              type="object",
     *              @SWG\Property(property="status", type="integer", description="Status code"),
     *              @SWG\Property(property="data", type="object",
     *                  @SWG\Property(property="status", type="string", description="Bid status"),
     *                  @SWG\Property(property="from_payment_system", type="string", description="from payment system"),
     *                  @SWG\Property(property="to_payment_system", type="string", description="to payment system"),
     *                  @SWG\Property(property="from wallet", type="string", description="from wallet"),
     *                  @SWG\Property(property="to wallet", type="string", description="to wallet"),
     *                  @SWG\Property(property="from_currency", type="string", description="from currency"),
     *                  @SWG\Property(property="to_currency", type="string", description="to currency"),
     *                  @SWG\Property(property="from_sum", type="integer", description="from sum"),
     *                  @SWG\Property(property="to_sum", type="integer", description="to sum")
     *              ),
     *         ),
     *         examples = {
     *              "status": 200,
     *              "data": {
     *                  "status": "accepted",
     *                  "from_payment_system": "yandex_money",
     *                  "to_payment_system": "privat24",
     *                  "from_wallet": "1234123412341234",
     *                  "to_wallet": "1234123412341234",
     *                  "from_currency": "rub",
     *                  "to_currency": "usd",
     *                  "from_sum": "123",
     *                  "to_sum": "123.5"
     *              }
     *         }
     *     ),
     *     @SWG\Response (
     *         response = 401,
     *         description = "Invalid credentials or Expired token"
     *     ),
     *     @SWG\Response(
     *         response = 404,
     *         description = "Bid not found"
     *     )
     * )
     *
     * @return array
     * @throws \yii\web\NotFoundHttpException
     */
    public function run()
    {
        /** @var \common\models\bid\BidEntity $bid */
        $bid = new $this->modelClass;

        return $bid->getBidDetails(\Yii::$app->request->get('id'));
    }
}
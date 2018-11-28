<?php

namespace rest\modules\api\v1\wallet\controllers\actions;

use yii\data\ArrayDataProvider;
use yii\rest\Action;
use common\models\wallet\WalletEntity;

/**
 * Class ListAction
 * @package rest\modules\api\v1\wallet\controllers\actions
 */
class ListAction extends Action
{
    /**
     * Lists all Wallet models
     *
     * @SWG\Get(path="/wallet",
     *      tags={"Wallet module"},
     *      summary="Get wallets by User",
     *      description="Get wallets by User",
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
     *         description = "OK",
     *         @SWG\Schema(
     *              type="object",
     *              @SWG\Property(property="status", type="integer", description="Status code"),
     *              @SWG\Property(property="message", type="string", description="Status message"),
     *              @SWG\Property(property="data", type="object",
     *                  @SWG\Property(property="id", type="integer", description="Wallet id"),
     *                  @SWG\Property(property="number", type="integer", description="Wallet number"),
     *                  @SWG\Property(property="created_by", type="integer", description="Author Wallet"),
     *                  @SWG\Property(property="name", type="string", description="Wallet name"),
     *                  @SWG\Property(property="payment_system", type="string", description="Payment system"),
     *                  @SWG\Property(property="created_at", type="integer", description="created at"),
     *                  @SWG\Property(property="updated_at", type="integer", description="updated at")
     *              ),
     *         ),
     *         examples = {
     *            "items": {
                      {
     *                  "status": 200,
     *                  "message": "",
     *                  "data": {
     *                     "id": 6,
     *                     "created_by": 2,
     *                     "name": "Мой первый шалон",
     *                     "number": "1234123412341234",
     *                     "payment_system": "yandex_money",
     *                     "created_at": 1520246365,
     *                     "updated_at": 1520246365
     *                  }
     *                }
     *           },
     *           "_links": {
     *               "self": {
     *                   "href": "http://ex.local.com/api/v1/wallet/list?page=1&per-page=10"
     *               }
     *           },
     *           "_meta": {
     *               "totalCount": 1,
     *               "pageCount": 1,
     *               "currentPage": 1,
     *               "perPage": 10
     *           }
     *        }
     *     )
     * )
     *
     * @return ArrayDataProvider
     */
    public function run(): ArrayDataProvider
    {
        /** @var WalletEntity $walletModel */
        $walletModel = new $this->modelClass();
        return $walletModel->getWallets(\Yii::$app->request->queryParams);
    }
}
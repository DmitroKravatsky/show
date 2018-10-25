<?php

namespace rest\modules\api\v1\exchangeRates\controllers\actions;

use common\models\exchangeRates\ExchangeRates;
use yii\rest\Action;
use yii\web\NotFoundHttpException;
use yii\web\ServerErrorHttpException;
use Yii;

class CalculateAmountAction extends Action
{
    /**
     * @SWG\Post(path="/exchange-rates/calculate-amount",
     *      tags={"Exchange Rates module"},
     *      summary="Transfer money by rate",
     *      description="Calculate the amount that the user will receive at the rate",
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *          in = "formData",
     *          name = "from_payment_system_id",
     *          description = "from payment system id",
     *          required = true,
     *          type = "integer",
     *      ),
     *      @SWG\Parameter(
     *          in = "formData",
     *          name = "to_payment_system_id",
     *          description = "to payment system id",
     *          required = true,
     *          type = "integer",
     *      ),
     *      @SWG\Parameter(
     *          in = "formData",
     *          name = "from_sum",
     *          description = "from sum",
     *          required = true,
     *          type = "integer"
     *      ),
     *      @SWG\Response(
     *         response = 200,
     *         description = "success",
     *         @SWG\Schema(
     *              type="object",
     *              @SWG\Property(property="items", type="object",
     *                   @SWG\Property(property="commission_sum", type="float", description="Amount including commission"),
     *                   @SWG\Property(property="to_sum", type="float", description="Amount including exchange rate")
     *              ),
     *         ),
     *         examples = {
     *             {
     *                 "commission_sum": 1190,
     *                 "to_sum": 1200.12
     *             }
     *         }
     *     ),
     *     @SWG\Response (
     *        response = 404,
     *        description = "Not found"
     *     ),
     *     @SWG\Response (
     *        response = 500,
     *        description = "Internal Server Error"
     *     )
     * )
     *
     * @throws NotFoundHttpException
     * @throws ServerErrorHttpException
     * @return array
     */
    public function run()
    {
        /** @var ExchangeRates $exchangeRates */
        $exchangeRates = new $this->modelClass;
        $params = Yii::$app->request->bodyParams;
        try {
            Yii::$app->getResponse()->setStatusCode(200);
            return [
                'status'  => Yii::$app->response->statusCode,
                'message' => '',
                'data'    => [
                    'commission_sum' => $params['from_sum'],
                    'to_sum' => $exchangeRates->calculateAmountByRate($params['from_payment_system_id'], $params['to_payment_system_id'], $params['from_sum'])
                ]
            ];
        } catch (NotFoundHttpException $e) {
            throw new NotFoundHttpException('Платежная система не найдена.');
        } catch (\Exception $e) {
            throw new ServerErrorHttpException('Произошла ошибка сервера.');
        }
    }
}


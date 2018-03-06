<?php

namespace rest\modules\api\v1\reserve\controllers\actions;

use common\models\reserve\ReserveEntity;
use rest\modules\api\v1\reserve\controllers\ReserveController;
use yii\rest\Action;
use yii\web\UnprocessableEntityHttpException;
use yii\web\ServerErrorHttpException;

/**
 * Class CreateAction
 * @package rest\modules\api\v1\reserve\controllers\actions
 */
class CreateAction extends Action
{
    /** @var  ReserveController */
    public $controller;

    /**
     * Creates a new Reserve model
     *
     * @SWG\Post(path="/reserve",
     *      tags={"Reserve module"},
     *      summary="Reserve create",
     *      description="Creates reserve",
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *          in = "formData",
     *          name = "payment_system",
     *          description = "from payment system",
     *          required = true,
     *          type = "string",
     *          enum = {"yandex_money", "web_money", "tincoff", "privat24", "sberbank", "qiwi"}
     *      ),
     *      @SWG\Parameter(
     *          in = "formData",
     *          name = "currency",
     *          description = "currency",
     *          required = true,
     *          type = "string",
     *          enum = {"usd", "rub", "uah", "eur"}
     *      ),
     *      @SWG\Parameter(
     *          in = "formData",
     *          name = "sum",
     *          description = "sum",
     *          required = true,
     *          type = "integer"
     *      ),
     *      @SWG\Response(
     *         response = 201,
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
     *                  @SWG\Property(property="created_at", type="integer", description="created at")
     *              ),
     *         ),
     *         examples = {
     *              "status": 201,
     *              "message": "Резервы успешно созданы.",
     *              "data": {
     *                  "id": 4,
     *                  "payment_system": "yandex_money",
     *                  "currency": "usd",
     *                  "sum": "10000",
     *                  "created_at": 1520252768
     *              }
     *         }
     *     ),
     *     @SWG\Response (
     *         response = 422,
     *         description = "Validation Error"
     *     ),
     *     @SWG\Response (
     *         response = 401,
     *         description = "Invalid credentials or Expired token"
     *     ),
     *     @SWG\Response(
     *         response = 500,
     *         description = "Internal Server Error"
     *     )
     * )
     *
     * @return array
     * @throws ServerErrorHttpException
     * @throws UnprocessableEntityHttpException
     */
    public function run(): array
    {
        try {
            /** @var ReserveEntity $reserveModel */
            $reserveModel = new $this->modelClass();
            $reserveModel = $reserveModel->createReserve(\Yii::$app->request->bodyParams);
            return $this->controller->setResponse(
                201,
                'Резервы успешно созданы.',
                $reserveModel->getAttributes(['id', 'payment_system', 'currency', 'sum', 'created_at'])
            );
        } catch (UnprocessableEntityHttpException $e) {
            throw new UnprocessableEntityHttpException($e->getMessage());
        } catch (\Exception $e) {
            \Yii::error($e->getMessage());
            throw new ServerErrorHttpException(\Yii::t('app', 'Произошла ошибка при создании заявки.'));
        }
    }
}
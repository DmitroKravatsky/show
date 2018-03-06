<?php

namespace rest\modules\api\v1\reserve\controllers\actions;

use common\models\reserve\ReserveEntity;
use rest\modules\api\v1\reserve\controllers\ReserveController;
use yii\rest\Action;
use Yii;
use yii\web\NotFoundHttpException;
use yii\web\ServerErrorHttpException;

/**
 * Class UpdateAction
 * @package rest\modules\api\v1\reserve\controllers\actions
 */
class UpdateAction extends Action
{
    /** @var  ReserveController */
    public $controller;

    /**
     * Updates an existing Reserve model
     *
     * @SWG\Put(path="/reserve/{id}",
     *      tags={"Reserve module"},
     *      summary="Reserve update",
     *      description="Updates reserve",
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *        in = "path",
     *        name = "id",
     *        description = "Reserve id",
     *        required = true,
     *        type = "integer"
     *      ),
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
     *              "status": 200,
     *              "message": "Резервы успешно изменены.",
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
     *      @SWG\Response (
     *         response = 404,
     *         description = "Reserve not found"
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
     * @param $id
     * @return array
     * @throws NotFoundHttpException
     * @throws ServerErrorHttpException
     */
    public function run($id): array 
    {
        try {
            /** @var ReserveEntity $reserveModel */
            $reserveModel = new $this->modelClass();
            $reserveModel = $reserveModel->updateReserve($id);
            return $this->controller->setResponse(
                200,
                Yii::t('app', 'Резервы успешно изменены.'),
                $reserveModel->getAttributes(['id', 'payment_system', 'currency', 'sum', 'created_at'])
            );
        } catch (NotFoundHttpException $e) {
            throw new NotFoundHttpException();
        } catch (\Exception $e) {
            Yii::error($e->getMessage());
            throw new ServerErrorHttpException(Yii::t('app', 'Произошла ошибка при изменении заявки.'));
        }
    }
}
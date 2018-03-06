<?php

namespace rest\modules\api\v1\bid\controllers\actions;

use common\models\bid\BidEntity;
use rest\modules\api\v1\bid\controllers\BidController;
use yii\rest\Action;
use yii\web\ServerErrorHttpException;
use yii\web\NotFoundHttpException;

/**
 * Class UpdateAction
 * @package rest\modules\api\v1\bid\controllers\actions
 */
class UpdateAction extends Action
{
    /** @var  BidController */
    public $controller;

    /**
     * Updates an existing bid model
     *
     * @SWG\Put(path="/bid/{id}",
     *      tags={"Bid module"},
     *      summary="Bid update",
     *      description="Update a user bid",
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
     *      @SWG\Parameter(
     *          in = "formData",
     *          name = "from_payment_system",
     *          description = "from payment system",
     *          required = false,
     *          type = "string",
     *          enum = {"yandex_money", "web_money", "tincoff", "privat24", "sberbank", "qiwi"}
     *      ),
     *      @SWG\Parameter(
     *          in = "formData",
     *          name = "to_payment_system",
     *          description = "to payment system",
     *          required = false,
     *          type = "string",
     *          enum = {"yandex_money", "web_money", "tincoff", "privat24", "sberbank", "qiwi"}
     *      ),
     *      @SWG\Parameter(
     *          in = "formData",
     *          name = "from_currency",
     *          description = "from currency",
     *          required = false,
     *          type = "string",
     *          enum = {"usd", "rub", "uah", "eur"}
     *      ),
     *      @SWG\Parameter(
     *          in = "formData",
     *          name = "to_currency",
     *          description = "to currency",
     *          required = false,
     *          type = "string",
     *          enum = {"usd", "rub", "uah", "eur"}
     *      ),
     *      @SWG\Parameter(
     *          in = "formData",
     *          name = "name",
     *          description = "User name",
     *          required = false,
     *          type = "string"
     *      ),
     *      @SWG\Parameter(
     *          in = "formData",
     *          name = "last_name",
     *          description = "User last name",
     *          required = false,
     *          type = "string"
     *      ),
     *      @SWG\Parameter(
     *          in = "formData",
     *          name = "phone_number",
     *          description = "User phone number",
     *          required = false,
     *          type = "string"
     *      ),
     *      @SWG\Parameter(
     *          in = "formData",
     *          name = "email",
     *          description = "User email",
     *          required = false,
     *          type = "string"
     *      ),
     *      @SWG\Parameter(
     *          in = "formData",
     *          name = "from_sum",
     *          description = "from sum",
     *          required = false,
     *          type = "integer"
     *      ),
     *      @SWG\Parameter(
     *          in = "formData",
     *          name = "to_sum",
     *          description = "to sum",
     *          required = false,
     *          type = "integer"
     *      ),
     *      @SWG\Parameter(
     *          in = "formData",
     *          name = "terms_confirm",
     *          description = "terms confirm",
     *          required = false,
     *          type = "integer",
     *          enum = {0, 1}
     *      ),
     *      @SWG\Parameter(
     *          in = "formData",
     *          name = "from_wallet",
     *          description = "from wallet",
     *          required = false,
     *          type = "string"
     *      ),
     *      @SWG\Parameter(
     *          in = "formData",
     *          name = "to_wallet",
     *          description = "to wallet",
     *          required = false,
     *          type = "string"
     *      ),
     *      @SWG\Response(
     *         response = 200,
     *         description = "success",
     *         @SWG\Schema(
     *              type="object",
     *              @SWG\Property(property="status", type="integer", description="Status code"),
     *              @SWG\Property(property="message", type="string", description="Status message"),
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
     *              "message": "Заявка успешно изменена.",
     *              "data": {
     *                  "id": 6,
     *                  "created_by": 5,
     *                  "name": "John",
     *                  "last_name": "Smith",
     *                  "phone_number": "+79787979879",
     *                  "email": "smith@gmail.com",
     *                  "status": null,
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
     * @param $id
     * @return array
     * @throws NotFoundHttpException
     * @throws ServerErrorHttpException
     */
    public function run($id): array
    {
        try {
            $bid = new BidEntity();
            if ($bid = $bid->updateBid($id, \Yii::$app->request->bodyParams)) {
                return $this->controller->setResponse(200, \Yii::t('app', 'Заявка успешно изменена.'), $bid->getAttributes());
            }
            throw new ServerErrorHttpException(\Yii::t('app', 'Произошла ошибка при изменении заявки.'));
        } catch (NotFoundHttpException $e) {
            throw new NotFoundHttpException();
        } catch (\Exception $e) {
            \Yii::error($e->getMessage());
            throw new ServerErrorHttpException();
        }
    }
}
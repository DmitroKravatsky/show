<?php

namespace rest\modules\api\v1\bid\controllers\actions;

use common\models\bid\BidEntity;
use rest\modules\api\v1\bid\controllers\BidController;
use yii\base\ErrorHandler;
use yii\rest\Action;
use yii\web\BadRequestHttpException;
use yii\web\ServerErrorHttpException;
use yii\web\UnprocessableEntityHttpException;
use Yii;

/**
 * Class CreateAction
 * @package rest\modules\api\v1\comment\controllers\actions
 */
class CreateAction extends Action
{
    /** @var  BidController */
    public $controller;

    /**
     * Creates a new Bid model
     *
     * @SWG\Post(path="/bid",
     *      tags={"Bid module"},
     *      summary="Bid create",
     *      description="Create a user bid",
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *        in = "header",
     *        name = "Authorization",
     *        description = "Authorization: Bearer &lt;token&gt;",
     *        required = true,
     *        type = "string"
     *      ),
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
     *          name = "name",
     *          description = "User name",
     *          required = true,
     *          type = "string"
     *      ),
     *      @SWG\Parameter(
     *          in = "formData",
     *          name = "last_name",
     *          description = "User last name",
     *          required = true,
     *          type = "string"
     *      ),
     *      @SWG\Parameter(
     *          in = "formData",
     *          name = "phone_number",
     *          description = "User phone number",
     *          required = true,
     *          type = "string"
     *      ),
     *      @SWG\Parameter(
     *          in = "formData",
     *          name = "email",
     *          description = "User email",
     *          required = true,
     *          type = "string"
     *      ),
     *      @SWG\Parameter(
     *          in = "formData",
     *          name = "from_sum",
     *          description = "from sum",
     *          required = true,
     *          type = "integer"
     *      ),
     *      @SWG\Parameter(
     *          in = "formData",
     *          name = "to_sum",
     *          description = "to sum",
     *          required = true,
     *          type = "integer"
     *      ),
     *      @SWG\Parameter(
     *          in = "formData",
     *          name = "terms_confirm",
     *          description = "terms confirm",
     *          required = true,
     *          type = "integer",
     *          enum = {0, 1}
     *      ),
     *      @SWG\Parameter(
     *          in = "formData",
     *          name = "from_wallet",
     *          description = "from wallet",
     *          required = true,
     *          type = "integer"
     *      ),
     *      @SWG\Parameter(
     *          in = "formData",
     *          name = "to_wallet",
     *          description = "to wallet",
     *          required = true,
     *          type = "integer"
     *      ),
     *      @SWG\Response(
     *         response = 200,
     *         description = "OK",
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
     *                  @SWG\Property(property="from_payment_system", type="integer", description="from payment system"),
     *                  @SWG\Property(property="to_payment_system", type="integer", description="to payment system"),
     *                  @SWG\Property(property="from wallet", type="string", description="from wallet"),
     *                  @SWG\Property(property="to wallet", type="string", description="to wallet"),
     *                  @SWG\Property(property="from_currency", type="string", description="from currency"),
     *                  @SWG\Property(property="to_currency", type="string", description="to currency"),
     *                  @SWG\Property(property="from_sum", type="integer", description="from sum"),
     *                  @SWG\Property(property="to_sum", type="integer", description="to sum"),
     *                  @SWG\Property(property="created_at", type="integer", description="created at")
     *              ),
     *         ),
     *         examples = {
     *              "status": 201,
     *              "message": "Your bid was successfully granted",
     *              "data": {
     *                  "id": 6,
     *                  "created_by": 5,
     *                  "name": "John",
     *                  "last_name": "Smith",
     *                  "phone_number": "+79787979879",
     *                  "email": "smith@gmail.com",
     *                  "status": null,
     *                  "from_payment_system": "Webmoney USD",
     *                  "to_payment_system": "Webmoney RUB",
     *                  "from_currency": "USD",
     *                  "to_currency": "RUB",
     *                  "from_sum": 123,
     *                  "to_sum": 123.5,
     *                  "created_at": 1520246365,
     *              }
     *         }
     *     ),
     *     @SWG\Response (
     *         response = 422,
     *         description = "Unprocessable Entity"
     *     ),
     *     @SWG\Response (
     *         response = 401,
     *         description = "Unauthorized"
     *     ),
     *     @SWG\Response (
     *         response = 400,
     *         description = "Bad Request"
     *     ),
     *     @SWG\Response(
     *         response = 500,
     *         description = "Internal Server Error"
     *     )
     * )
     *
     * @return array
     * @throws BadRequestHttpException
     * @throws ServerErrorHttpException
     * @throws UnprocessableEntityHttpException
     */
    public function run(): array
    {
        try {
            /** @var BidEntity $bid */
            $bid = new $this->modelClass;
            $dataResult = $bid->createBid(Yii::$app->request->bodyParams);

            Yii::$app->getResponse()->setStatusCode(201, 'Your bid was successfully granted');
            return [
                'status' => Yii::$app->response->statusCode,
                'message' => Yii::t('app', 'Your bid was successfully granted'),
                'data' => $dataResult
            ];
        } catch (BadRequestHttpException $e) {
            Yii::$app->getResponse()->setStatusCode($e->statusCode);
            return [
                'status' => Yii::$app->response->statusCode,
                'message' => Yii::t('app', $e->getMessage()),
                'data' => ['user_exist' => true]
            ];
        } catch (UnprocessableEntityHttpException $e) {
            throw new UnprocessableEntityHttpException($e->getMessage());
        } catch (\Exception $e) {
            Yii::error(ErrorHandler::convertExceptionToString($e));
            throw new ServerErrorHttpException(Yii::t('app', 'Something is wrong, please try again later'));
        }
    }
}
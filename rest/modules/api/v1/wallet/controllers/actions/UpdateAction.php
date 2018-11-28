<?php

namespace rest\modules\api\v1\wallet\controllers\actions;

use common\models\wallet\WalletEntity;
use rest\modules\api\v1\wallet\controllers\WalletController;
use yii\rest\Action;
use yii\web\NotFoundHttpException;
use yii\web\ServerErrorHttpException;
use yii\web\UnprocessableEntityHttpException;

/**
 * Class UpdateAction
 * @package rest\modules\api\v1\wallet\controllers\actions
 */
class UpdateAction extends Action
{
    /** @var  WalletController */
    public $controller;

    /**
     * Updates an existing Wallet model
     *
     * @SWG\Put(path="/wallet/{id}",
     *      tags={"Wallet module"},
     *      summary="Wallet update",
     *      description="Updates a user wallet",
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
     *        description = "Wallet id",
     *        required = true,
     *        type = "integer"
     *      ),
     *      @SWG\Parameter(
     *          in = "formData",
     *          name = "payment_system_id",
     *          description = "payment system id",
     *          required = false,
     *          type = "integer"
     *      ),
     *      @SWG\Parameter(
     *          in = "formData",
     *          name = "number",
     *          description = "Wallet number",
     *          required = false,
     *          type = "string"
     *      ),
     *      @SWG\Parameter(
     *          in = "formData",
     *          name = "name",
     *          description = "Wallet name",
     *          required = false,
     *          type = "string"
     *      ),
     *      @SWG\Response(
     *         response = 201,
     *         description = "success",
     *         @SWG\Schema(
     *              type="object",
     *              @SWG\Property(property="status", type="integer", description="Status code"),
     *              @SWG\Property(property="message", type="string", description="Status message"),
     *              @SWG\Property(property="data", type="object",
     *                  @SWG\Property(property="id", type="integer", description="Wallet id"),
     *                  @SWG\Property(property="number", type="integer", description="Wallet number"),
     *                  @SWG\Property(property="name", type="string", description="Wallet name"),
     *                  @SWG\Property(property="payment_system_id", type="integer", description="Payment system id"),
     *                  @SWG\Property(property="created_at", type="integer", description="created at")
     *              ),
     *         ),
     *         examples = {
     *              "status": 200,
     *              "message": "Wallet layout was successfully updated",
     *              "data": {
     *                  "id": 6,
     *                  "name": "Мой первый шалон",
     *                  "number": "1234123412341234",
     *                  "payment_system_id": 6,
     *                  "created_at": 1520246365
     *              }
     *         }
     *     ),
     *     @SWG\Response (
     *         response = 404,
     *         description = "Not Found"
     *     ),
     *     @SWG\Response (
     *         response = 422,
     *         description = "Unprocessable Entity"
     *     ),
     *     @SWG\Response (
     *         response = 401,
     *         description = "Unauthorized"
     *     ),
     *      @SWG\Response (
     *         response = 403,
     *         description = "Forbidden"
     *     ),
     *     @SWG\Response(
     *         response = 500,
     *         description = "Internal Server Error"
     *     )
     * )
     *
     * @param int $id
     *
     * @return array
     *
     * @throws NotFoundHttpException
     * @throws UnprocessableEntityHttpException
     * @throws ServerErrorHttpException
     */
    public function run(int $id): array
    {
        try {
            /** @var WalletEntity $walletModel */
            $walletModel = new $this->modelClass();
            $walletModel = $walletModel->updateWallet($id, \Yii::$app->request->bodyParams);

            \Yii::$app->getResponse()->setStatusCode(200);

            return [
                'status'  => \Yii::$app->response->statusCode,
                'message' => 'Wallet layout was successfully updated',
                'data'    => $walletModel->getAttributes(['id', 'name', 'number', 'payment_system_id', 'created_at'])
            ];
        } catch (NotFoundHttpException $e) {
            throw new NotFoundHttpException($e->getMessage());
        } catch (UnprocessableEntityHttpException $e) {
            throw new UnprocessableEntityHttpException($e->getMessage());
        } catch (\Exception $e) {
            \Yii::error($e->getMessage());
            throw new ServerErrorHttpException(\Yii::t('app', 'Something is wrong, please try again later'));
        }
    }
}
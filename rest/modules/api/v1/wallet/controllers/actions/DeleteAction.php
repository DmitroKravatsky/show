<?php

namespace rest\modules\api\v1\wallet\controllers\actions;

use rest\modules\api\v1\wallet\controllers\WalletController;
use yii\rest\Action;
use common\models\wallet\WalletEntity;
use yii\web\ServerErrorHttpException;
use yii\web\NotFoundHttpException;

/**
 * Class DeleteAction
 * @package rest\modules\api\v1\wallet\controllers\actions
 */
class DeleteAction extends Action
{
    /** @var  WalletController */
    public $controller;

    /**
     * Deletes an existing Wallet model
     *
     * @SWG\Delete(path="/wallet/{id}",
     *      tags={"Wallet module"},
     *      summary="Deletes wallet",
     *      description="Deletes wallet",
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
     *      @SWG\Response(
     *         response = 200,
     *         description = "success",
     *         @SWG\Schema(
     *              type="object",
     *              @SWG\Property(property="status", type="integer", description="Status code"),
     *              @SWG\Property(property="message", type="string", description="Status message"),
     *              @SWG\Property(property="data", type="object",
     *                  @SWG\Property(property="id", type="integer", description="Wallet id")
     *              ),
     *         ),
     *         examples = {
     *              "status": 200,
     *              "message": "Шаблон кошелька успешно удалён.",
     *              "data": {
     *                  "id": 6
     *              }
     *         }
     *     ),
     *     @SWG\Response (
     *         response = 401,
     *         description = "Invalid credentials or Expired token"
     *     ),
     *     @SWG\Response (
     *         response = 403,
     *         description = "Forbidden"
     *     ),
     *     @SWG\Response (
     *         response = 404,
     *         description = "Not found"
     *     ),
     *     @SWG\Response(
     *         response = 500,
     *         description = "Internal Server Error"
     *     )
     * )
     * @param $id
     *
     * @return array
     * 
     * @throws NotFoundHttpException
     * @throws ServerErrorHttpException
     */
    public function run($id): array 
    {
        try {
            /** @var WalletEntity $walletModel */
            $walletModel = new $this->modelClass();
            if ($walletModel->deleteWallet($id)) {
                $response = \Yii::$app->getResponse();
                $response->setStatusCode(200, \Yii::t('app', 'Layout was successfully deleted'));
                return [
                    'status'  => $response->statusCode,
                    'message' => $response->statusText,
                    'data'    => ['id' => $id]
                ];
            }
            throw new ServerErrorHttpException(\Yii::t('app', 'Произошла ошибка при удалении шаблона кошелька.'));
        } catch (NotFoundHttpException $e) {
            throw new NotFoundHttpException($e->getMessage());
        } catch (\Exception $e) {
            \Yii::error($e->getMessage());
            throw new ServerErrorHttpException(\Yii::t('app', 'Произошла ошибка при удалении шаблона кошелька.'));
        }
    }
}
<?php

namespace rest\modules\api\v1\wallet\controllers\actions;

use rest\modules\api\v1\wallet\controllers\WalletController;
use yii\rest\Action;
use common\models\wallet\WalletEntity;
use yii\web\ServerErrorHttpException;
use yii\web\NotFoundHttpException;
use Yii;

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
     * @param $id
     * @return array
     * @throws NotFoundHttpException
     * @throws ServerErrorHttpException
     */
    public function run($id): array 
    {
        try {
            /** @var WalletEntity $walletModel */
            $walletModel = new $this->modelClass();
            if ($walletModel->deleteWallet($id)) {
                return $this->controller->setResponse(200, 'Шаблон кошелька успешно удалён.', ['id' => $id]);
            }
            throw new ServerErrorHttpException(Yii::t('app', 'Произошла ошибка при удалении шаблона кошелька.'));
        } catch (NotFoundHttpException $e) {
            throw new NotFoundHttpException($e->getMessage());
        } catch (\Exception $e) {
            Yii::error($e->getMessage());
            throw new ServerErrorHttpException(Yii::t('app', 'Произошла ошибка при удалении шаблона кошелька.'));
        }
    }
}
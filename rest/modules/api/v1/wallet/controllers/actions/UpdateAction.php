<?php

namespace rest\modules\api\v1\wallet\controllers\actions;

use common\models\wallet\WalletEntity;
use rest\modules\api\v1\wallet\controllers\WalletController;
use yii\rest\Action;
use yii\web\NotFoundHttpException;
use yii\web\ServerErrorHttpException;
use Yii;

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
     * @param int $id
     * @return array
     * @throws NotFoundHttpException
     * @throws ServerErrorHttpException
     */
    public function run(int $id): array
    {
        try {
            /** @var WalletEntity $walletModel */
            $walletModel = new $this->modelClass();
            $walletModel = $walletModel->updateWallet($id, Yii::$app->request->bodyParams);
            
            return $this->controller->setResponse(
                200,
                'Шаблон кошелька успешно изменён.',
                $walletModel->getAttributes(['id', 'name', 'number', 'payment_system', 'created_at'])
            );
        } catch (NotFoundHttpException $e) {
            throw new NotFoundHttpException($e->getMessage());
        } catch (\Exception $e) {
            Yii::error($e->getMessage());
            throw new ServerErrorHttpException(Yii::t('app', 'Произошла ошибка при изменении отзыва.'));
        }
    }
}
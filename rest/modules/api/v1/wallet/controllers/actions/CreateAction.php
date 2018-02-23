<?php

namespace rest\modules\api\v1\wallet\controllers\actions;

use common\models\wallet\WalletEntity;
use rest\modules\api\v1\wallet\controllers\WalletController;
use yii\rest\Action;
use yii\web\UnprocessableEntityHttpException;
use yii\web\ServerErrorHttpException;
use Yii;

/**
 * Class CreateAction
 * @package rest\modules\api\v1\wallet\controllers\actions
 */
class CreateAction extends Action
{
    /** @var  WalletController */
    public $controller;

    /**
     * Creates a new Wallet model
     *
     * @return array
     * @throws ServerErrorHttpException
     * @throws UnprocessableEntityHttpException
     */
    public function run(): array
    {
        try {
            /** @var WalletEntity $walletModel */
            $walletModel = new $this->modelClass();
            $walletModel = $walletModel->createWallet(\Yii::$app->request->bodyParams);
            return $this->controller->setResponse(
                201,
                'Шаблон кошелька успешно создан.',
                $walletModel->getAttributes(['id', 'name', 'number', 'payment_system', 'created_at'])
            );
        } catch (UnprocessableEntityHttpException $e) {
            throw new UnprocessableEntityHttpException($e->getMessage());
        } catch (\Exception $e) {
            Yii::error($e->getMessage());
            throw new ServerErrorHttpException(Yii::t('app', 'Произошла ошибка при создании шаблона кошелька.'));
        }
    }
}
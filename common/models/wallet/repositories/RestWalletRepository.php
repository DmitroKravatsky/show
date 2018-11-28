<?php

namespace common\models\wallet\repositories;

use common\models\wallet\WalletEntity;
use yii\data\ArrayDataProvider;
use yii\db\ActiveQuery;
use yii\web\NotFoundHttpException;

/**
 * Class RestWalletRepository
 * @package common\models\wallet\repositories
 */
trait RestWalletRepository
{
    /**
     * Add new wallet to db with the set of income data
     *
     * @param $params array of the POST data
     *
     * @return WalletEntity
     *
     * @throws \yii\web\UnprocessableEntityHttpException
     */
    public function createWallet(array $params): WalletEntity
    {
        $walletModel = new WalletEntity();
        $walletModel->setAttributes($params);

        if (!$walletModel->save()) {
            $this->throwModelException($walletModel->errors);
        }

        return $walletModel;
    }

    /**
     * Updates a wallet by Wallet id and User id
     *
     * @param $id int
     * @param $params array of the POST data
     *
     * @return WalletEntity
     *
     * @throws NotFoundHttpException if there is no such wallet
     * @throws \yii\web\UnprocessableEntityHttpException
     */
    public function updateWallet(int $id, array $params): WalletEntity
    {
        /** @var WalletEntity $walletModel */
        $walletModel = $this->findModel(['id' => (int) $id, 'created_by' => \Yii::$app->user->id]);
        $walletModel->setAttributes($params);

        if (!$walletModel->save()) {
            $this->throwModelException($walletModel->errors);
        }
        $walletModel->payment_system_id = (int) $walletModel->payment_system_id;

        return $walletModel;
    }

    /**
     * Removes a wallet by Wallet id and User id
     *
     * @param $id int
     *
     * @return bool
     *
     * @throws NotFoundHttpException if there is no such wallet
     * @throws \Exception
     * @throws \Throwable
     */
    public function deleteWallet(int $id): bool
    {
        /** @var WalletEntity $walletModel */
        $walletModel = $this->findModel(['id' => $id, 'created_by' => \Yii::$app->user->id]);
        if ($walletModel->delete()) {
            return true;
        }
        return false;
    }

    /**
     * Returns list of wallets by User id
     *
     * @param array $params
     * 
     * @return ArrayDataProvider
     */
    public function getWallets(array $params): ArrayDataProvider
    {
        /** @var ActiveQuery $query */
        $query = self::find()->where(['created_by' => \Yii::$app->user->id]);

        $dataProvider = new ArrayDataProvider([
            'allModels'  => $query->orderBy(['created_at' => SORT_DESC])->all(),
            'pagination' => [
                'pageSize' => $params['per-page'] ?? 10
            ]
        ]);

        return $dataProvider;
    }

    /**
     * Finds an existing Wallet model
     *
     * @param array $params
     *
     * @return mixed
     *
     * @throws NotFoundHttpException if there is no such wallet
     */
    protected function findModel(array $params)
    {
        if (!empty($walletModel = static::findOne($params))) {
            return $walletModel;
        }

        throw new NotFoundHttpException('Шаблон не найден.');
    }
}
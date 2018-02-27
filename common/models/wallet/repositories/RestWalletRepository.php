<?php

namespace common\models\wallet\repositories;

use common\models\wallet\WalletEntity;
use Yii;
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
     * @param $params
     * @return WalletEntity
     * @throws \yii\web\UnprocessableEntityHttpException
     */
    public function createWallet($params): WalletEntity
    {
        $walletModel = new WalletEntity();
        $walletModel->setAttributes($params);

        if (!$walletModel->save()) {
            $this->throwModelException($walletModel->errors);
        }

        return $walletModel;
    }

    /**
     * @param $id
     * @param $params
     * @return WalletEntity
     * @throws NotFoundHttpException
     * @throws \yii\web\UnprocessableEntityHttpException
     */
    public function updateWallet($id, $params): WalletEntity
    {
        /** @var WalletEntity $walletModel */
        $walletModel = $this->findModel(['id' => (int) $id, 'created_by' => Yii::$app->user->id]);
        $walletModel->setAttributes($params);

        if (!$walletModel->save()) {
            $this->throwModelException($walletModel->errors);
        }

        return $walletModel;
    }

    /**
     * @param $id
     * @return bool
     * @throws NotFoundHttpException
     * @throws \Exception
     * @throws \Throwable
     */
    public function deleteWallet($id): bool
    {
        /** @var WalletEntity $walletModel */
        $walletModel = $this->findModel(['id' => (int) $id, 'created_by' => Yii::$app->user->id]);
        if ($walletModel->delete()) {
            return true;
        }
        return false;
    }

    /**
     * @param array $params
     * @return ArrayDataProvider
     */
    public function getWallets(array $params): ArrayDataProvider
    {
        /** @var ActiveQuery $query */
        $query = self::find()->where(['created_by' => Yii::$app->user->id]);

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
     * @return mixed
     * @throws NotFoundHttpException
     */
    protected function findModel(array $params)
    {
        if (!empty($walletModel = static::findOne($params))) {
            return $walletModel;
        }

        throw new NotFoundHttpException('Шаблон не найден.');
    }
}
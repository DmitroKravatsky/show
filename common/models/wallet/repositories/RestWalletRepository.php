<?php

namespace common\models\wallet\repositories;

use common\models\wallet\WalletEntity;
use Yii;
use yii\data\ArrayDataProvider;
use yii\db\ActiveQuery;
use yii\web\NotFoundHttpException;
use yii\web\ServerErrorHttpException;

/**
 * Class RestWalletRepository
 * @package common\models\wallet\repositories
 */
trait RestWalletRepository
{
    /**
     * @param array $params
     * @return mixed
     * @throws \yii\web\UnprocessableEntityHttpException
     */
    public function createWallet(array $params)
    {
        $walletModel = new WalletEntity();
        $walletModel->setAttributes($params);

        if (!$walletModel->save()) {
            $this->throwModelException($walletModel->errors);
        }

        return $this->setResponse(
            201,
            'Шаблон кошелька успешно создан.',
            $walletModel->getAttributes(['id', 'name', 'number', 'payment_system', 'created_at'])
        );

    }

    /**
     * @param $id
     * @return array
     * @throws NotFoundHttpException
     * @throws \yii\web\UnprocessableEntityHttpException
     */
    public function updateWallet($id): array 
    {
        /** @var WalletEntity $walletModel */
        $walletModel = $this->findModel(['id' => $id, 'created_by' => Yii::$app->user->id]);
        $walletModel->setAttributes(Yii::$app->request->bodyParams);

        if (!$walletModel->save()) {
            $this->throwModelException($walletModel->errors);
        }

        return $this->setResponse(
            200,
            'Шаблон кошелька успешно изменён.',
            $walletModel->getAttributes(['id', 'name', 'number', 'payment_system', 'created_at'])
        );
    }

    /**
     * @param $id
     * @return mixed
     * @throws NotFoundHttpException
     * @throws ServerErrorHttpException
     * @throws \Exception
     * @throws \Throwable
     */
    public function deleteWallet($id)
    {
        /** @var WalletEntity $walletModel */
        $walletModel = $this->findModel(['id' => $id, 'created_by' => Yii::$app->user->id]);

        if ($walletModel->delete()) {
            return $this->setResponse(200, 'Шаблон кошелька успешно удалён.', ['id' => $id]);
        }

        throw new ServerErrorHttpException('Произошла ошибка при удалении шаблона кошелька.');
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
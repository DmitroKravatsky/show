<?php

namespace common\models\reserve\repositories;

use common\models\paymentSystem\PaymentSystem;
use common\models\reserve\ReserveEntity;
use yii\data\ArrayDataProvider;
use yii\web\NotFoundHttpException;

/**
 * Class RestReserveRepository
 * @package common\models\reserve\repositories
 */
trait RestReserveRepository
{
    /**
     * Add new reserve to db with the set of income data
     * 
     * @param $params array of the POST data
     *
     * @return ReserveEntity
     *
     * @throws \yii\web\UnprocessableEntityHttpException
     */
    public function createReserve(array $params): ReserveEntity
    {
        $reserveModel = new self();
        $reserveModel->setAttributes($params);
        if (!$reserveModel->save()) {
            $this->throwModelException($reserveModel->errors);
        }

        return $reserveModel;
    }

    /**
     * Updates a reserve by id
     *
     * @param $id int
     * @param $postData array of the POST data
     *
     * @return ReserveEntity
     *
     * @throws NotFoundHttpException if there is no such reserve
     * @throws \yii\web\UnprocessableEntityHttpException
     */
    public function updateReserve(int $id, array $postData): ReserveEntity
    {
        $reserveModel = $this->findModel(['id' => $id]);

        $reserveModel->setAttributes($postData);
        if (!$reserveModel->save()) {
            $this->throwModelException($reserveModel->errors);
        }

        return $reserveModel;
    }

    public function getList($params)
    {
        $query = ReserveEntity::find()
            ->joinWith('paymentSystem')
            ->select([static::tableName() . '.id', 'currency', 'sum', 'name', 'payment_system_id'])
            ->where([static::tableName() . '.visible' => self::VISIBLE_YES]);

        if (isset($params['filter'])) {
            $query->andWhere(['currency' => $params['filter']]);
        }

        $reserves = $query->orderBy([self::tableName() . '.created_at' => SORT_DESC])->all();

        $result = [];
        foreach ($reserves as $reserve) {
            /** @var ReserveEntity $reserve */
            $result[] = [
                'id'             => $reserve->id,
                'payment_system' => $reserve->paymentSystem->name,
                'currency'       => PaymentSystem::getCurrencyValue($reserve->paymentSystem->currency),
                'sum'            => round($reserve->sum, 2),
            ];
        }

        $dataProvider = new ArrayDataProvider([
            'allModels' => $result,
            'pagination' => [
                'pageSize' => $params['per-page'] ?? \Yii::$app->params['posts-per-page'],
                'page' => isset($params['page']) ? $params['page'] - 1 : 0,
            ]
        ]);

        return $dataProvider;
    }

    /**
     * Finds an existing Reserve model
     *
     * @param $params array
     *
     * @return ReserveEntity
     * 
     * @throws NotFoundHttpException if there is no such reserve
     */
    protected function findModel(array $params): ReserveEntity
    {
        if (empty($reserveModel = self::findOne($params))) {
            throw new NotFoundHttpException('Резервы не найдены.');
        }

        return $reserveModel;
    }
}
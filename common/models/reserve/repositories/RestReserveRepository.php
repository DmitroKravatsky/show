<?php

namespace common\models\reserve\repositories;

use common\models\reserve\ReserveEntity;
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
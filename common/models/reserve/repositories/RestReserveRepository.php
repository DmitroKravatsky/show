<?php

namespace common\models\reserve\repositories;

use common\models\reserve\ReserveEntity;
use Yii;
use yii\web\NotFoundHttpException;

/**
 * Class RestReserveRepository
 * @package common\models\reserve\repositories
 */
trait RestReserveRepository
{
    /**
     * @param $params
     * @return ReserveEntity
     * @throws \yii\web\UnprocessableEntityHttpException
     */
    public function createReserve($params): ReserveEntity
    {
        $reserveModel = new self();
        $reserveModel->setAttributes($params);
        if (!$reserveModel->save()) {
            $this->throwModelException($reserveModel->errors);
        }

        return $reserveModel;
    }

    /**
     * @param $id
     * @return ReserveEntity
     * @throws NotFoundHttpException
     * @throws \yii\web\UnprocessableEntityHttpException
     */
    public function updateReserve($id): ReserveEntity
    {
        $reserveModel = $this->findModel((int) $id);

        $reserveModel->setAttributes(Yii::$app->request->bodyParams);
        if (!$reserveModel->save()) {
            $this->throwModelException($reserveModel->errors);
        }

        return $reserveModel;
    }

    /**
     * Finds an existing Reserve model
     *
     * @param $params
     * @return ReserveEntity
     * @throws NotFoundHttpException
     */
    protected function findModel($params): ReserveEntity
    {
        if (empty($reserveModel = self::findOne($params))) {
            throw new NotFoundHttpException('Резервы не найдены.');
        }

        return $reserveModel;
    }
}
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
     * @return mixed
     * @throws \yii\web\UnprocessableEntityHttpException
     */
    public function createReserve()
    {
        $reserveModel = new self();
        $reserveModel->setAttributes(Yii::$app->request->bodyParams);
        if (!$reserveModel->save()) {
            $this->throwModelException($reserveModel->errors);
        }

        return $this->setResponse(
            201,
            'Резервы успешно созданы.',
            $reserveModel->getAttributes(['id', 'payment_system', 'currency', 'sum', 'created_at'])
        );
    }

    /**
     * @param $id
     * @return mixed
     * @throws NotFoundHttpException
     * @throws \yii\web\UnprocessableEntityHttpException
     */
    public function updateReserve($id)
    {
        $reserveModel = $this->findModel((int) $id);

        $reserveModel->setAttributes(Yii::$app->request->bodyParams);
        if ($reserveModel->save()) {
            return $this->setResponse(
                200,
                Yii::t('app', 'Резервы успешно изменены.'),
                $reserveModel->getAttributes(['id', 'payment_system', 'currency', 'sum', 'created_at'])
            );
        }

        return $this->throwModelException($reserveModel->errors);
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
<?php

namespace common\models\reserve\repositories;

use Yii;

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
}
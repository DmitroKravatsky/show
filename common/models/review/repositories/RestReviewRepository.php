<?php

namespace common\models\review\repositories;

use common\models\review\ReviewEntity;
use yii\web\NotFoundHttpException;

/**
 * Class RestReviewRepository
 * @package common\models\review\repositories
 */
trait RestReviewRepository
{
    /**
     * @param $params
     * @return mixed
     * @throws \yii\web\UnprocessableEntityHttpException
     */
    public function create($params)
    {
        $reviewModel = new self;
        $reviewModel->setAttributes($params);
        if (!$reviewModel->save()) {
            $this->throwModelException($reviewModel->errors);
        }

        return $this->setResponse(201, 'Отзыв успешно добавлен.', $reviewModel->getAttributes(['id', 'text']));
    }

    /**
     * @param $id
     * @param $params
     * @return mixed
     * @throws NotFoundHttpException
     * @throws \yii\web\UnprocessableEntityHttpException
     */
    public function updateReview($id, $params)
    {
        if (empty($reviewModel = ReviewEntity::findOne(['id' => $id, 'created_by' => \Yii::$app->user->id]))) {
            throw new NotFoundHttpException('Отзыв не найден.');
        }

        $reviewModel->setAttributes($params);
        if (!$reviewModel->save()) {
            $this->throwModelException($reviewModel->errors);
        }

        return $this->setResponse(200, 'Отзыв успешно изменён.', $reviewModel->getAttributes(['id', 'text']));
    }
}
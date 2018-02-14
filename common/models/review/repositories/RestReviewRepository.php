<?php

namespace common\models\review\repositories;

use common\models\review\ReviewEntity;
use yii\data\ArrayDataProvider;
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

    /**
     * @param $params
     * @return ArrayDataProvider
     */
    public function listReviews($params): ArrayDataProvider
    {
        $reviews = ReviewEntity::find()
            ->select(['name', 'text', 'review.created_at'])
            ->leftJoin('user_profile', 'review.created_by = user_profile.user_id')
            ->orderBy(['review.created_at' => SORT_DESC])
            ->asArray()
            ->all();

        $dataProvider = new ArrayDataProvider([
            'allModels' => $reviews,
            'pagination' => [
                'pageSize' => $params['per-page'] ?? 10,
            ],
        ]);

        return $dataProvider;
    }
}
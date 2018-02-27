<?php

namespace common\models\review\repositories;

use common\models\review\ReviewEntity;
use yii\data\ArrayDataProvider;
use yii\db\BaseActiveRecord;
use yii\web\NotFoundHttpException;
use Yii;

/**
 * Class RestReviewRepository
 * @package common\models\review\repositories
 */
trait RestReviewRepository
{
    /**
     * @param $params
     * @return ReviewEntity
     * @throws \yii\web\UnprocessableEntityHttpException
     */
    public function create($params): ReviewEntity
    {
        $reviewModel = new self;
        $reviewModel->setAttributes($params);
        if (!$reviewModel->save()) {
            $this->throwModelException($reviewModel->errors);
        }

        return $reviewModel;
    }

    /**
     * @param $id
     * @param $params
     * @return ReviewEntity
     * @throws NotFoundHttpException
     * @throws \yii\web\UnprocessableEntityHttpException
     */
    public function updateReview($id, $params): ReviewEntity
    {
        $reviewModel = $this->findModel(['id' => (int) $id, 'created_by' => Yii::$app->user->id]);
        $reviewModel->setAttributes($params);

        if (!$reviewModel->save()) {
            $this->throwModelException($reviewModel->errors);
        }

        return $reviewModel;
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

    /**
     * @param $id
     * @return bool
     * @throws NotFoundHttpException
     * @throws \yii\db\StaleObjectException
     */
    public function deleteReview($id): bool
    {
        $reviewModel = $this->findModel(['id' => (int) $id, 'created_by' => Yii::$app->user->id]);
        if ($reviewModel->delete()) {
            return true;
        }
        return false;
    }

    /**
     * @param $params
     * @return BaseActiveRecord
     * @throws NotFoundHttpException
     */
    public function findModel($params): BaseActiveRecord
    {
        if (empty($reviewModel = self::findOne($params))) {
            throw new NotFoundHttpException('Отзыв не найден');
        }

        return $reviewModel;
    }
}
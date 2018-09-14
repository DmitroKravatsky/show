<?php

namespace common\models\review\repositories;

use common\models\review\ReviewEntity;
use rest\modules\api\v1\authorization\models\RestUserEntity;
use yii\data\ArrayDataProvider;
use yii\db\BaseActiveRecord;
use yii\web\{ ForbiddenHttpException, NotFoundHttpException };
use Yii;

/**
 * Class RestReviewRepository
 * @package common\models\review\repositories
 */
trait RestReviewRepository
{
    /**
     * Add new review to db with the set of income data
     *
     * @param $params array of the POST data
     *
     * @return ReviewEntity
     *
     * @throws \yii\web\UnprocessableEntityHttpException
     * @throws \yii\web\ForbiddenHttpException
     */
    public function create(array $params): ReviewEntity
    {
        $user = RestUserEntity::findIdentity(Yii::$app->user->id);
        if (!$user->hasBids()) {
            throw new ForbiddenHttpException(\Yii::t('app', 'You must have at least one bid to write a review'));
        }
        $reviewModel = new ReviewEntity();
        $reviewModel->setAttributes($params);
        if (!$reviewModel->save()) {
            $this->throwModelException($reviewModel->errors);
        }

        return $reviewModel;

    }

    /**
     * Updates an existing Review model by Review id and User id
     *
     * @param $id int
     * @param $params array of the POST data
     *
     * @return ReviewEntity
     *
     * @throws NotFoundHttpException
     * @throws \yii\web\UnprocessableEntityHttpException
     */
    public function updateReview(int $id, array $params): ReviewEntity
    {
        $reviewModel = $this->findModel(['id' => $id, 'created_by' => \Yii::$app->user->id]);
        $reviewModel->setAttributes($params);

        if (!$reviewModel->save()) {
            $this->throwModelException($reviewModel->errors);
        }

        return $reviewModel;
    }

    /**
     * Returns list of reviews
     *
     * @param $params array
     *
     * @return ArrayDataProvider
     */
    public function listReviews(array $params): ArrayDataProvider
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
     * Removes a Review by Review id and User id
     *
     * @param $id int
     *
     * @return bool
     *
     * @throws NotFoundHttpException
     * @throws \yii\db\StaleObjectException
     */
    public function deleteReview(int $id): bool
    {
        $reviewModel = $this->findModel(['id' => $id, 'created_by' => \Yii::$app->user->id]);
        if ($reviewModel->delete()) {
            return true;
        }
        return false;
    }

    /**
     * Finds a Review model by params
     *
     * @param $params array
     *
     * @return BaseActiveRecord
     *
     * @throws NotFoundHttpException
     */
    public function findModel(array $params): BaseActiveRecord
    {
        if (empty($reviewModel = self::findOne($params))) {
            throw new NotFoundHttpException('Отзыв не найден');
        }

        return $reviewModel;
    }
}

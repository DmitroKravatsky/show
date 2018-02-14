<?php

namespace common\models\review\repositories;

use common\models\review\ReviewEntity;
use yii\data\ArrayDataProvider;
use yii\db\BaseActiveRecord;
use yii\web\NotFoundHttpException;
use Yii;
use yii\web\ServerErrorHttpException;

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
        $reviewModel = $this->findModel(['id' => $id, 'created_by' => Yii::$app->user->id]);
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

    /**
     * @param $id
     * @return mixed
     * @throws NotFoundHttpException
     * @throws ServerErrorHttpException
     * @throws \yii\db\StaleObjectException
     */
    public function deleteReview($id)
    {
        $reviewModel = $this->findModel(['id' => $id, 'created_by' => Yii::$app->user->id]);
        if ($reviewModel->delete()) {
            return $this->setResponse(200, 'Отзыв успешно удалён.');
        }

        throw new ServerErrorHttpException('Произошла ошибка при удалении отзыва.');
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
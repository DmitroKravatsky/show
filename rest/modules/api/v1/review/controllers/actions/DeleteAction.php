<?php

namespace rest\modules\api\v1\review\controllers\actions;

use common\models\review\ReviewEntity;
use rest\modules\api\v1\review\controllers\ReviewController;
use yii\rest\Action;
use yii\web\NotFoundHttpException;
use yii\web\ServerErrorHttpException;
use Yii;

/**
 * Class DeleteAction
 * @package rest\modules\api\v1\review\controllers\actions
 */
class DeleteAction extends Action
{
    /** @var  ReviewController */
    public $controller;

    /**
     * Deletes an existing Review model
     *
     * @param $id
     * @return array
     * @throws NotFoundHttpException
     * @throws ServerErrorHttpException
     */
    public function run($id): array
    {
        try {
            $reviewModel = new ReviewEntity();
            if ($reviewModel->deleteReview($id)) {
                return $this->controller->setResponse(200, 'Отзыв успешно удалён.', ['id' => $id]);
            }
            throw new ServerErrorHttpException(Yii::t('app', 'Произошла ошибка при удалении отзыва.'));
        } catch (NotFoundHttpException $e) {
            throw new NotFoundHttpException($e->getMessage());
        } catch (\Exception $e) {
            Yii::error($e->getMessage());
            throw new ServerErrorHttpException(Yii::t('app', 'Произошла ошибка при удалении отзыва.'));
        }
    }
}
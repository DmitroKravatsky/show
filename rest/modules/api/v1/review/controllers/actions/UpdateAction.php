<?php

namespace rest\modules\api\v1\review\controllers\actions;

use common\models\review\ReviewEntity;
use rest\modules\api\v1\review\controllers\ReviewController;
use yii\rest\Action;
use yii\web\NotFoundHttpException;
use yii\web\ServerErrorHttpException;
use Yii;

/**
 * Class UpdateAction
 * @package rest\modules\api\v1\review\controllers\actions
 */
class UpdateAction extends Action
{
    /** @var  ReviewController */
    public $controller;

    /**
     * Updates an existing Review model
     * 
     * @param $id
     * @return array
     * @throws NotFoundHttpException
     * @throws ServerErrorHttpException
     */
    public function run($id)
    {
        try {
            /** @var ReviewEntity $reviewModel */
            $reviewModel = new $this->modelClass;
            $reviewModel = $reviewModel->updateReview($id, Yii::$app->request->bodyParams);
            return $this->controller->setResponse(200, 'Отзыв успешно изменён.', $reviewModel->getAttributes(['id', 'text']));
        } catch (NotFoundHttpException $e) {
            throw new NotFoundHttpException($e->getMessage());
        } catch (\Exception $e) {
            Yii::error($e->getMessage());
            throw new ServerErrorHttpException(Yii::t('app', 'Произошла ошибка при изменении отзыва.'));
        }
    }
}
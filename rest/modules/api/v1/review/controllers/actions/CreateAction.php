<?php

namespace rest\modules\api\v1\review\controllers\actions;

use common\models\review\ReviewEntity;
use rest\modules\api\v1\review\controllers\ReviewController;
use yii\rest\Action;
use yii\web\UnprocessableEntityHttpException;
use yii\web\ServerErrorHttpException;
use Yii;

/**
 * Class CreateAction
 * @package rest\modules\api\v1\review\controllers\actions
 */
class CreateAction extends Action
{
    /** @var  ReviewController */
    public $controller;

    /**
     * @return array
     * @throws ServerErrorHttpException
     * @throws UnprocessableEntityHttpException
     */
    public function run(): array
    {
        try {
            /** @var ReviewEntity $reviewModel */
            $reviewModel = new $this->modelClass;
            $reviewModel = $reviewModel->create(\Yii::$app->request->bodyParams);
            
            return $this->controller->setResponse(201, 'Отзыв успешно добавлен.', $reviewModel->getAttributes(['id', 'text']));
        } catch (UnprocessableEntityHttpException $e) {
            throw new UnprocessableEntityHttpException($e->getMessage());
        } catch (\Exception $e) {
            Yii::error($e->getMessage());
            throw new ServerErrorHttpException(Yii::t('app', 'Произошла ошибка при создании заявки.'));
        }
    }
}
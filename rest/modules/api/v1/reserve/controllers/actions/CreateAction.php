<?php

namespace rest\modules\api\v1\reserve\controllers\actions;

use common\models\reserve\ReserveEntity;
use rest\modules\api\v1\reserve\controllers\ReserveController;
use yii\rest\Action;
use Yii;
use yii\web\UnprocessableEntityHttpException;
use yii\web\ServerErrorHttpException;

/**
 * Class CreateAction
 * @package rest\modules\api\v1\reserve\controllers\actions
 */
class CreateAction extends Action
{
    /** @var  ReserveController */
    public $controller;

    /**
     * Creates a new Reserve model
     *
     * @return array
     * @throws ServerErrorHttpException
     * @throws UnprocessableEntityHttpException
     */
    public function run(): array
    {
        try {
            /** @var ReserveEntity $reserveModel */
            $reserveModel = new $this->modelClass();
            $reserveModel = $reserveModel->createReserve(Yii::$app->request->bodyParams);
            return $this->controller->setResponse(
                201,
                'Резервы успешно созданы.',
                $reserveModel->getAttributes(['id', 'payment_system', 'currency', 'sum', 'created_at'])
            );
        } catch (UnprocessableEntityHttpException $e) {
            throw new UnprocessableEntityHttpException($e->getMessage());
        } catch (\Exception $e) {
            Yii::error($e->getMessage());
            throw new ServerErrorHttpException(Yii::t('app', 'Произошла ошибка при создании заявки.'));
        }
    }
}
<?php

namespace rest\modules\api\v1\reserve\controllers\actions;

use common\models\reserve\ReserveEntity;
use rest\modules\api\v1\reserve\controllers\ReserveController;
use yii\rest\Action;
use Yii;
use yii\web\NotFoundHttpException;
use yii\web\ServerErrorHttpException;

/**
 * Class UpdateAction
 * @package rest\modules\api\v1\reserve\controllers\actions
 */
class UpdateAction extends Action
{
    /** @var  ReserveController */
    public $controller;

    /**
     * Updates an existing Reserve model
     *
     * @param $id
     * @return array
     * @throws NotFoundHttpException
     * @throws ServerErrorHttpException
     */
    public function run($id): array 
    {
        try {
            /** @var ReserveEntity $reserveModel */
            $reserveModel = new $this->modelClass();
            $reserveModel = $reserveModel->updateReserve($id);
            return $this->controller->setResponse(
                200,
                Yii::t('app', 'Резервы успешно изменены.'),
                $reserveModel->getAttributes(['id', 'payment_system', 'currency', 'sum', 'created_at'])
            );
        } catch (NotFoundHttpException $e) {
            throw new NotFoundHttpException();
        } catch (\Exception $e) {
            Yii::error($e->getMessage());
            throw new ServerErrorHttpException(Yii::t('app', 'Произошла ошибка при изменении заявки.'));
        }
    }
}
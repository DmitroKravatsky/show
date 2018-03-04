<?php

namespace rest\modules\api\v1\bid\controllers\actions;

use common\models\bid\BidEntity;
use rest\modules\api\v1\bid\controllers\BidController;
use Yii;
use yii\rest\Action;
use yii\web\ServerErrorHttpException;
use yii\web\NotFoundHttpException;

/**
 * Class UpdateAction
 * @package rest\modules\api\v1\bid\controllers\actions
 */
class UpdateAction extends Action
{
    /** @var  BidController */
    public $controller;

    /**
     * Updates an existing bid model
     *
     * @param $id
     * @return array
     * @throws NotFoundHttpException
     * @throws ServerErrorHttpException
     */
    public function run($id): array
    {
        try {
            $bid = new BidEntity();
            if ($bid = $bid->updateBid($id)) {
                return $this->controller->setResponse(200, Yii::t('app', 'Заявка успешно изменена.'), $bid->getAttributes());
            }
            throw new ServerErrorHttpException(Yii::t('app', 'Произошла ошибка при изменении заявки.'));
        } catch (NotFoundHttpException $e) {
            throw new NotFoundHttpException();
        } catch (\Exception $e) {
            Yii::error($e->getMessage());
            throw new ServerErrorHttpException();
        }
    }
}
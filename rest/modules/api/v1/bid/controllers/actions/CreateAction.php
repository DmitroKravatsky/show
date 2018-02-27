<?php

namespace rest\modules\api\v1\bid\controllers\actions;

use common\models\bid\BidEntity;
use rest\modules\api\v1\bid\controllers\BidController;
use yii\web\ServerErrorHttpException;
use Yii;
use yii\web\UnprocessableEntityHttpException;

/**
 * Class CreateAction
 * @package rest\modules\api\v1\comment\controllers\actions
 */
class CreateAction extends \yii\rest\Action
{
    /** @var  BidController */
    public $controller;

    /**
     * Creates a new Bid model
     *
     * @return array
     * @throws ServerErrorHttpException
     * @throws UnprocessableEntityHttpException
     */
    public function run(): array
    {
        try {
            /** @var BidEntity $bid */
            $bid = new $this->modelClass;
            $bid = $bid->createBid();

            return $this->controller->setResponse(201, Yii::t('app', 'Заявка успешно добавлена.'), $bid->getAttributes());
        } catch (UnprocessableEntityHttpException $e) {
            throw new UnprocessableEntityHttpException($e->getMessage());
        } catch (\Exception $e) {
            Yii::error($e->getMessage());
            throw new ServerErrorHttpException(Yii::t('app', 'Произошла ошибка при создании заявки.'));
        }
    }
}
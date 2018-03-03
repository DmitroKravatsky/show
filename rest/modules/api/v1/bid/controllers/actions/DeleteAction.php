<?php

namespace rest\modules\api\v1\bid\controllers\actions;

use common\models\bid\BidEntity;
use rest\modules\api\v1\bid\controllers\BidController;
use yii\web\NotFoundHttpException;
use yii\web\ServerErrorHttpException;
use Yii;

/**
 * Class DeleteAction
 * @package rest\modules\api\v1\bid\controllers\actions
 */
class DeleteAction extends \yii\rest\Action // todo исправить
{
    /** @var  BidController */
    public $controller;

    /**
     * Deletes an existing Bid model
     *
     * @param $id
     * @return array
     * @throws NotFoundHttpException
     * @throws ServerErrorHttpException
     */
    public function run($id): array 
    {
        try {
            /** @var BidEntity $bid */
            $bid = new BidEntity();
            if ($bid->deleteBid($id)) {
                return $this->controller->setResponse(200, Yii::t('app', 'Заявка успешно удалёна.'), ['id' => $id]);
            }
            throw new ServerErrorHttpException(Yii::t('app', 'Произошла ошибка при удалении заявки.'));
        } catch (NotFoundHttpException $e) {
            throw new NotFoundHttpException();
        } catch (\Exception $e) {
            Yii::error($e->getMessage());
            throw new ServerErrorHttpException();
        }
    }
}
<?php

namespace rest\modules\api\v1\bid\controllers\actions;

use common\models\bid\BidEntity;
use rest\modules\api\v1\bid\controllers\BidController;
use yii\web\{ NotFoundHttpException, ServerErrorHttpException };
use Yii;

/**
 * Class DeleteAction
 * @package rest\modules\api\v1\bid\controllers\actions
 */
class DeleteAction extends \yii\rest\Action
{
    /** @var  BidController */
    public $controller;

    /**
     * @param $id
     * @return array
     * @throws NotFoundHttpException
     * @throws ServerErrorHttpException
     * @throws \Exception
     * @throws \Throwable
     */
    public function run($id)
    {
        /** @var BidEntity $bid */
        $bid = $this->findModel($id);
        if ($bid->delete()) {
            return $this->controller->setResponse(200, Yii::t('app', 'Заявка успешно удалёна.'), ['id' => $id]);
        }

        throw new ServerErrorHttpException(Yii::t('app', 'Произошла ошибка при удалении заявки.'));
    }
}
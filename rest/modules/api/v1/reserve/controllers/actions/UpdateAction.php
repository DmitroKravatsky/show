<?php

namespace rest\modules\api\v1\reserve\controllers\actions;

use common\models\reserve\ReserveEntity;
use yii\rest\Action;
use Yii;

/**
 * Class UpdateAction
 * @package rest\modules\api\v1\reserve\controllers\actions
 */
class UpdateAction extends Action
{
    /**
     * Updates an existing Reserve model
     *
     * @param $id
     * @return mixed
     */
    public function run($id)
    {
        /** @var ReserveEntity $reserveModel */
        $reserveModel = new $this->modelClass();
        return $reserveModel->updateReserve($id);
    }
}
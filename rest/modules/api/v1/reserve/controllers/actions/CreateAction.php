<?php

namespace rest\modules\api\v1\reserve\controllers\actions;

use common\models\reserve\ReserveEntity;
use yii\rest\Action;

/**
 * Class CreateAction
 * @package rest\modules\api\v1\reserve\controllers\actions
 */
class CreateAction extends Action
{
    /**
     * Creates a new Reserve model
     *
     * @return mixed
     */
    public function run()
    {
        /** @var ReserveEntity $reserveModel */
        $reserveModel = new $this->modelClass();
        return $reserveModel->createReserve();
    }
}
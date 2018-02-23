<?php

namespace rest\modules\api\v1\wallet\controllers\actions;

use yii\rest\Action;
use common\models\wallet\WalletEntity;

/**
 * Class DeleteAction
 * @package rest\modules\api\v1\wallet\controllers\actions
 */
class DeleteAction extends Action
{
    /**
     * Deletes an existing Wallet model
     * 
     * @param int $id
     * @return mixed
     * @throws \yii\web\ServerErrorHttpException
     */
    public function run(int $id)
    {
        /** @var WalletEntity $walletModel */
        $walletModel = new $this->modelClass();
        return $walletModel->deleteWallet($id);
    }
}
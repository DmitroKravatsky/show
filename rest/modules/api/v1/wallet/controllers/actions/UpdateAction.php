<?php

namespace rest\modules\api\v1\wallet\controllers\actions;

use common\models\wallet\WalletEntity;
use yii\rest\Action;

/**
 * Class UpdateAction
 * @package rest\modules\api\v1\wallet\controllers\actions
 */
class UpdateAction extends Action
{
    /**
     * Updates an existing Wallet model
     * 
     * @param int $id
     * @return array
     */
    public function run(int $id): array
    {
        /** @var WalletEntity $walletModel */
        $walletModel = new $this->modelClass();
        return $walletModel->updateWallet($id);
    }
}
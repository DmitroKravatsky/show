<?php

namespace rest\modules\api\v1\wallet\controllers\actions;

use common\models\wallet\WalletEntity;
use yii\rest\Action;

/**
 * Class CreateAction
 * @package rest\modules\api\v1\wallet\controllers\actions
 */
class CreateAction extends Action
{
    /**
     * Creates a new Wallet model
     * @return mixed
     */
    public function run()
    {
        /** @var WalletEntity $walletModel */
        $walletModel = new $this->modelClass();
        return $walletModel->createWallet(\Yii::$app->request->bodyParams);
    }
}
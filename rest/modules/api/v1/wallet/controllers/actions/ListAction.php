<?php

namespace rest\modules\api\v1\wallet\controllers\actions;

use yii\data\ArrayDataProvider;
use yii\rest\Action;
use common\models\wallet\WalletEntity;

/**
 * Class ListAction
 * @package rest\modules\api\v1\wallet\controllers\actions
 */
class ListAction extends Action
{
    /**
     * Lists all Wallet models
     *
     * @return ArrayDataProvider
     */
    public function run(): ArrayDataProvider
    {
        /** @var WalletEntity $walletModel */
        $walletModel = new $this->modelClass();
        return $walletModel->getWallets(\Yii::$app->request->queryParams);
    }
}
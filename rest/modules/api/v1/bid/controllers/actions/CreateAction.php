<?php

namespace rest\modules\api\v1\bid\controllers\actions;

use common\behaviors\ValidatePostParameters;
use common\models\bid\BidEntity;
use rest\modules\api\v1\bid\controllers\BidController;
use yii\web\ServerErrorHttpException;
use Yii;

/**
 * Class CreateAction
 * @mixin ValidatePostParameters
 * @package rest\modules\api\v1\comment\controllers\actions
 */
class CreateAction extends \yii\rest\Action
{
    /** @var  BidController */
    public $controller;

    /**
     * @var array
     */
    public $params = [];

    /**
     * @return array
     */
    public function behaviors(): array
    {
        return [
            'reportParams' => [
                'class'       => ValidatePostParameters::className(),
                'inputParams' => [
                    'from_wallet', 'to_wallet', 'from_currency', 'to_currency', 'name', 'last_name', 'email',
                    'phone_number', 'from_sum', 'to_sum','from_payment_system', 'to_payment_system'
                ]
            ],
        ];
    }

    /**
     * @return bool
     * @throws \yii\web\BadRequestHttpException
     */
    protected function beforeRun(): bool
    {
        $this->validationParams();
        return parent::beforeRun();
    }

    /**
     * @return mixed
     * @throws ServerErrorHttpException
     */
    public function run()
    {
        /** @var BidEntity $bid */
        $bid = new $this->modelClass;
        return $bid->createBid();
    }
}
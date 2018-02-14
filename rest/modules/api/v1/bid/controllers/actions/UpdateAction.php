<?php

namespace rest\modules\api\v1\bid\controllers\actions;

use common\behaviors\ValidatePostParameters;
use common\models\bid\BidEntity;
use Yii;
use yii\web\ServerErrorHttpException;

/**
 * Class UpdateAction
 * @mixin ValidatePostParameters
 * @package rest\modules\api\v1\bid\controllers\actions
 */
class UpdateAction extends \yii\rest\Action
{
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
                    'phone_number', 'from_sum', 'to_sum', 'from_payment_system', 'to_payment_system',
                ],
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
     * @param $id
     * @return array
     * @throws ServerErrorHttpException
     * @throws \yii\web\NotFoundHttpException
     * @throws \yii\web\UnprocessableEntityHttpException
     */
    public function run($id)
    {
        /** @var BidEntity $bid */
        $bid = new BidEntity();
        
        return $bid->updateBid($id);
    }
}
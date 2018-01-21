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
     * @inheritdoc
     */
    public function behaviors(): array
    {
        return [
            'reportParams' => [
                'class' => ValidatePostParameters::className(),
                'inputParams' => [
                    'from_wallet', 'to_wallet', 'from_currency', 'to_currency', 'name', 'last_name', 'email',
                    'phone_number'
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
     * @throws ServerErrorHttpException
     * @throws \yii\db\Exception
     */
    public function run()
    {
        /** @var BidEntity $bid */
        $bid = new $this->modelClass;
        $bid->setScenario(BidEntity::SCENARIO_CREATE);
        $bid->setAttributes(Yii::$app->request->bodyParams);
        if ($bid->save()) {
            return $this->controller->setResponse(201, Yii::t('app', 'Заявка успешно добавлена.'), $bid->getAttributes());
        } elseif ($bid->hasErrors()) {
            $this->controller->throwModelException($bid->errors);
        }

        throw new ServerErrorHttpException(Yii::t('app', 'Произошла ошибка при добавлении заявки.'));
    }
}
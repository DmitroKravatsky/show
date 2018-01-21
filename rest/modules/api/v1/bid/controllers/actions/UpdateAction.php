<?php

namespace rest\modules\api\v1\bid\controllers\actions;

use common\behaviors\ValidatePostParameters;
use common\models\bid\BidEntity;
use rest\modules\api\v1\bid\controllers\BidController;
use Yii;
use yii\web\ServerErrorHttpException;

/**
 * Class UpdateAction
 * @mixin ValidatePostParameters
 * @package rest\modules\api\v1\bid\controllers\actions
 */
class UpdateAction extends \yii\rest\Action
{
    /** @var BidController */
    public $controller;

    /**
     * @var array
     */
    public $params = [];

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'reportParams' => [
                'class' => ValidatePostParameters::className(),
                'inputParams' => [
                    'from_wallet', 'to_wallet', 'from_currency', 'to_currency', 'name', 'last_name', 'email',
                    'phone_number'
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
        $bid = $this->findModel($id);
        $bid->setScenario(BidEntity::SCENARIO_UPDATE);
        $bid->setAttributes(Yii::$app->request->bodyParams);
        if ($bid->save()) {
            return $this->controller->setResponse(200, Yii::t('app', 'Заявка успешно изменена.'), $bid->getAttributes());
        } elseif ($bid->hasErrors()) {
            $this->controller->throwModelException($bid->errors);
        }

        throw new ServerErrorHttpException(Yii::t('app', 'Произошла ошибка при изменена заявки.'));
    }
}
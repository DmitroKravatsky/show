<?php

namespace rest\modules\api\v1\reserve\controllers\actions;

use common\behaviors\ValidatePostParameters;
use common\models\reserve\ReserveEntity;
use rest\modules\api\v1\reserve\controllers\ReserveController;
use yii\rest\Action;
use yii\web\NotFoundHttpException;
use yii\web\ServerErrorHttpException;
use Yii;

/**
 * Class UpdateAction
 * @package rest\modules\api\v1\reserve\controllers\actions
 * @mixin ValidatePostParameters
 */
class UpdateAction extends Action
{
    /** @var ReserveController */
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
                    'payment_system', 'currency', 'sum',
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
     * @throws NotFoundHttpException
     * @throws ServerErrorHttpException
     * @throws \yii\web\UnprocessableEntityHttpException
     */
    public function run($id)
    {
        if (empty($reserve = ReserveEntity::findOne($id))) {
            throw new NotFoundHttpException('Резервы не найдены.');
        }

        $reserve->setAttributes(Yii::$app->request->bodyParams);
        if ($reserve->save()) {
            return $this->controller->setResponse(200, Yii::t('app', 'Резервы успешно изменены.'), $reserve->getAttributes());
        } elseif ($reserve->hasErrors()) {
            $this->controller->throwModelException($reserve->errors);
        }

        throw new ServerErrorHttpException(Yii::t('app', 'Произошла ошибка при изменена резервов.'));
    }
}
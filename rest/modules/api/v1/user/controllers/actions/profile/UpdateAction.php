<?php

namespace rest\modules\api\v1\user\controllers\actions\profile;

use common\models\userProfile\UserProfileEntity;
use rest\modules\api\v1\user\controllers\UserProfileController;
use yii\rest\Action;
use common\behaviors\ValidatePostParameters;
use Yii;
use yii\web\NotFoundHttpException;
use yii\web\ServerErrorHttpException;

/**
 * Class UpdateAction
 * @package rest\modules\api\v1\user\controllers\actions\profile
 * @mixin ValidatePostParameters
 */
class UpdateAction extends Action
{
    /** @var  UserProfileController */
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
                    'name', 'last_name', 'email', 'phone_number',
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
     * Updates an existing model
     * @param $id
     * @return array
     * @throws ServerErrorHttpException
     * @throws \yii\web\NotFoundHttpException
     * @throws \yii\web\UnprocessableEntityHttpException
     */
    public function run($id)
    {
        /* @var $model UserProfileEntity */
        if (empty($model = UserProfileEntity::findOne(['id' => $id, 'user_id' => Yii::$app->user->id]))) {
            throw new NotFoundHttpException(Yii::t('app', 'Профиль не найден.'));
        }

        $model->scenario = UserProfileEntity::SCENARIO_UPDATE;
        $model->load(Yii::$app->request->bodyParams, '');

        if ($model->save()) {
            return $this->controller->setResponse(200, Yii::t('app', 'Профиль успешно изменён.'), $model->getAttributes());
        } elseif ($model->hasErrors()) {
            $this->controller->throwModelException($model->errors);
        }

        throw new ServerErrorHttpException(Yii::t('app', 'Произошла ошибка при изменении профиля.'));
    }
}
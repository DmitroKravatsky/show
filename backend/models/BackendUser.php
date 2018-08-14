<?php

namespace backend\models;

use common\models\user\User;
use Yii;

class BackendUser extends User
{
    const SCENARIO_REGISTER = 1;
    const SCENARIO_UPDATE_PASSWORD = 2;

    public $repeatPassword;
    public $currentPassword;

    /**
     * @return array
     */
    public function rules(): array
    {
        return [
            [['password',], 'required'],
            ['email', 'email'],
            [['password', 'repeatPassword'], 'string', 'min' => 6],
            [['repeatPassword'], 'compare', 'compareAttribute' => 'password'],
            [['created_at', 'updated_at', 'refresh_token', 'status'], 'safe'],
            [['repeatPassword',], 'required', 'on' => [self::SCENARIO_REGISTER, self::SCENARIO_UPDATE_PASSWORD]],
            [['currentPassword',], 'required', 'on' => [self::SCENARIO_UPDATE_PASSWORD]],
            [['currentPassword'], 'checkCurrentPassword'],
        ];
    }

    /**
     * @param bool $insert
     * @return bool
     * @throws \yii\base\Exception
     */
    public function beforeSave($insert): bool
    {
        if (!parent::beforeSave($insert)) {
            return false;
        }

        if (self::SCENARIO_UPDATE_PASSWORD == $this->scenario) {
            $this->password = Yii::$app->security->generatePasswordHash($this->password);
        }
        return true;
    }

    /**
     * @param $attribute
     * @param $params
     */
    public function checkCurrentPassword($attribute, $params)
    {
        if (!Yii::$app->security->validatePassword($this->{$attribute}, self::findOne(Yii::$app->user->id)->password)) {
            $this->addError($attribute, Yii::t('app', 'Invalid old password.'));
        }
    }

    /**
     * @return array
     */
    public static function getUsernames(): array
    {
        return static::find()->leftJoin('user_profile', 'user_id = user.id')->select(['name', 'user.id'])->indexBy('id')->column();
    }

    /**
     * Returns all available user statuses
     * @return array
     */
    public static function getStatusLabels(): array
    {
        return [
            self::STATUS_BANNED     => Yii::t('app', 'Banned'),
            self::STATUS_UNVERIFIED => Yii::t('app', 'Unverified'),
            self::STATUS_VERIFIED   => Yii::t('app', 'Verified'),

        ];
    }

}

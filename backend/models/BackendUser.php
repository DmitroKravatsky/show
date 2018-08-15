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
            [['password', 'email',], 'required'],
            [['email', 'new_email',], 'email'],
            [['email'], 'checkEmailExistence'],
            [['verification_token'], 'string', 'max' => 255],
            [['password', 'repeatPassword'], 'string', 'min' => 6],
            [['repeatPassword'], 'compare', 'compareAttribute' => 'password'],
            [['created_at', 'updated_at', 'refresh_token', 'status'], 'safe'],
            [['repeatPassword',], 'required', 'on' => [self::SCENARIO_REGISTER, self::SCENARIO_UPDATE_PASSWORD]],
            [['currentPassword',], 'required', 'on' => [self::SCENARIO_UPDATE_PASSWORD]],
            [['currentPassword'], 'checkCurrentPassword'],
            [['verification_code'], 'integer'],
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
     * Finds user by token
     * @param string $token
     * @return BackendUser|null
     */
    public static function findByVerificationToken($token)
    {
        if (($user = static::findOne(['verification_token' => $token])) !== null) {
            return $user;
        }
        return null;
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

    public function checkEmailExistence($attribute, $params)
    {
        $user = static::find()->where(['email' => $this->email])->andWhere(['!=', 'id', Yii::$app->user->id])->one();
        if ($user !== null) {
            $this->addError($attribute, Yii::t('app', 'This email address has already been taken.'));
        }
    }

    /**
     * @return array
     */
    public static function getUsernames(): array
    {
        return static::find()->leftJoin('user_profile', 'user_id = user.id')->select(['name', 'user.id'])->indexBy('id')->column();
    }
}

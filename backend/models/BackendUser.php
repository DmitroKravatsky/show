<?php

namespace backend\models;

use common\models\user\User;
use Yii;
use borales\extensions\phoneInput\PhoneInputValidator;

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
            [['password', 'email', 'phone_number',], 'required'],
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
            ['phone_number', PhoneInputValidator::class],
            ['phone_number', 'checkPhoneNumberExistence'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'password'         => Yii::t('app', 'Password'),
            'currentPassword'  => Yii::t('app', 'Current Password'),
            'repeatPassword'   => Yii::t('app', 'Repeat Password'),
            'phone_number'     => Yii::t('app', 'Phone Number'),
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

    public function checkPhoneNumberExistence($attribute, $params)
    {
        $user = static::find()->where(['phone_number' => $this->phone_number])->andWhere(['!=', 'id', Yii::$app->user->id])->one();
        if ($user !== null) {
            $this->addError($attribute, Yii::t('app', 'This phone number address has already been taken.'));
        }
    }

    /**
     * @return array
     */
    public static function getUsernames(): array
    {
        return static::find()
            ->leftJoin('user_profile', 'user_id = user.id')
            ->select(['name', 'user.id'])
            ->indexBy('id')
            ->column();
    }

    /**
     * @param $inviteCode
     * @return BackendUser|null
     */
    public static function findByInviteCode($inviteCode)
    {
        return static::findOne(['invite_code' => $inviteCode]);
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

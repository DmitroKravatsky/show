<?php

namespace backend\models;

use common\models\user\User;
use Yii;
use borales\extensions\phoneInput\PhoneInputValidator;

class BackendUser extends User
{
    const SCENARIO_REGISTER = 1;
    const SCENARIO_UPDATE_PASSWORD = 2;
    const SCENARIO_UPDATE_PASSWORD_BY_ADMIN = 3;

    public $repeatPassword;
    public $currentPassword;
    public $newPassword;

    /**
     * @return array
     */
    public function rules(): array
    {
        return [
            [['password', 'email', 'phone_number',], 'required', 'except' => self::SCENARIO_UPDATE_PASSWORD_BY_ADMIN],
            [['email', 'new_email',], 'email'],
            [['email'], 'checkEmailExistence', 'except' => self::SCENARIO_UPDATE_PASSWORD_BY_ADMIN],
            [['verification_token'], 'string', 'max' => 255],
            [['password', 'repeatPassword', 'newPassword'], 'string', 'min' => 6],
            [
                ['repeatPassword'],
                'compare',
                'compareAttribute' => 'password',
                'on' => [self::SCENARIO_REGISTER, self::SCENARIO_UPDATE_PASSWORD]],
            [
                'repeatPassword',
                'compare',
                'compareAttribute' => 'newPassword',
                'on' => self::SCENARIO_UPDATE_PASSWORD_BY_ADMIN
            ],
            [['newPassword', 'repeatPassword'], 'required', 'on' => [self::SCENARIO_UPDATE_PASSWORD_BY_ADMIN]],
            [['created_at', 'updated_at', 'refresh_token', 'status'], 'safe'],
            [['repeatPassword',], 'required', 'on' => [self::SCENARIO_REGISTER, self::SCENARIO_UPDATE_PASSWORD]],
            [['currentPassword',], 'required', 'on' => [self::SCENARIO_UPDATE_PASSWORD]],
            [['currentPassword'], 'checkCurrentPassword'],
            [['verification_code', 'status_online', 'last_login', 'accept_invite',], 'integer'],
            ['phone_number', PhoneInputValidator::class],
            ['phone_number', 'checkPhoneNumberExistence', 'except' => self::SCENARIO_UPDATE_PASSWORD_BY_ADMIN],
        ];
    }

    public function attributeLabels()
    {
        return [
            'password'         => Yii::t('app', 'Password'),
            'newPassword'         => Yii::t('app', 'New Password'),
            'currentPassword'  => Yii::t('app', 'Current Password'),
            'repeatPassword'   => Yii::t('app', 'Repeat Password'),
            'phone_number'     => Yii::t('app', 'Phone Number'),
            'source'           => Yii::t('app', 'Source'),
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

        if ($this->scenario === self::SCENARIO_UPDATE_PASSWORD) {
            $this->password = Yii::$app->security->generatePasswordHash($this->password);
        }

        if ($this->scenario === self::SCENARIO_UPDATE_PASSWORD_BY_ADMIN) {
            $this->password = Yii::$app->security->generatePasswordHash($this->newPassword);
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
     * @param User $user
     * @return bool
     */
    public function toggleVerifiedStatus(User $user)
    {
        if ($user->status === User::STATUS_VERIFIED) {
            $user->status = User::STATUS_UNVERIFIED;
        } else {
            $user->status = User::STATUS_VERIFIED;
        }
        return $user->save(false, ['status']);
    }

    /**
     * @return array
     */
    public static function getManagerNames(): array
    {
        return static::find()
            ->leftJoin('user_profile', 'user_id = user.id')
            ->innerJoin('auth_assignment', 'auth_assignment.user_id = user.id')
            ->select(['name', 'user.id'])
            ->where(['in', 'auth_assignment.item_name', [self::ROLE_MANAGER, self::ROLE_ADMIN]])
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

    /**
     * @param $source
     * @return string
     */
    public static function getRegistrationMethodLabel($source): string
    {
        if ($source === self::SOCIAL) {
            return Yii::t('app', 'Social Network');
        }
        return Yii::t('app', 'Application');
    }
}

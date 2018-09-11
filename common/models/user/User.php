<?php

namespace common\models\user;

use common\models\userNotifications\NotificationsEntity;
use common\models\userNotifications\repositories\RestUserNotificationsRepository;
use common\models\userProfile\UserProfileEntity;
use yii\base\NotSupportedException;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;
use Yii;

/**
 * User model
 *
 * @property integer $id
 * @property string $password
 * @property string $confirm_password
 * @property string $password_reset_token
 * @property string $email
 * @property string $auth_key
 * @property string $source
 * @property string $source_id
 * @property string $phone_number
 * @property integer $terms_condition
 * @property string $refresh_token
 * @property integer $created_refresh_token
 * @property integer $created_at
 * @property integer $updated_at
 * @property integer $recovery_code
 * @property integer $created_recovery_code
 * @property integer $status
 * @property integer $verification_code
 * @property string  $invite_code
 * @property integer $invite_code_status
 * @property integer $verification_token
 * @property integer $new_email
 * @property integer $status_online
 * @property integer $last_login
 * @property integer $accept_invite
 * @property integer $register_by_bid
 *
 * @property UserProfileEntity $profile
 */
class User extends ActiveRecord implements IdentityInterface
{
    use RestUserNotificationsRepository;

    const ROLE_ADMIN   = 'admin';
    const ROLE_GUEST   = 'guest';
    const ROLE_MANAGER = 'manager';
    const ROLE_USER    = 'user';

    const DEFAULT_GUEST_ID = 1;
    const DEFAULT_ADMIN_ID = 2;

    const STATUS_UNVERIFIED = 'UNVERIFIED';
    const STATUS_VERIFIED   = 'VERIFIED';
    const STATUS_BANNED     = 'BANNED';

    const STATUS_INVITE_ACTIVE = 'ACTIVE';
    const STATUS_INVITE_INACTIVE = 'INACTIVE';

    const STATUS_ONLINE_NO = 0;
    const STATUS_ONLINE_YES = 1;

    const ACCEPT_INVITE_NO  = 0;
    const ACCEPT_INVITE_YES = 1;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%user}}';
    }

    /**
     * Returns Invite statuses
     * @return array
     */
    public static function getInviteStatuses(): array
    {
        return [
            self::STATUS_INVITE_ACTIVE => Yii::t('app', 'Active'),
            self::STATUS_INVITE_INACTIVE => Yii::t('app', 'Inactive'),
        ];
    }

    /**
     * Returns status online labels
     * @return array
     */
    public static function getStatusOnlineLabels(): array
    {
        return [
            self::STATUS_ONLINE_NO => Yii::t('app', 'No'),
            self::STATUS_ONLINE_YES => Yii::t('app', 'Yes'),
        ];
    }

    public static function getAcceptInviteLabels(): array
    {
        return [
            self::ACCEPT_INVITE_NO => Yii::t('app', 'No'),
            self::ACCEPT_INVITE_YES => Yii::t('app', 'Yes'),
        ];
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            TimestampBehavior::class,
        ];
    }

    /**
     * @inheritdoc
     */
    public static function findIdentity($id)
    {
        return static::findOne(['id' => $id]);
    }

    /**
     * @inheritdoc
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        throw new NotSupportedException('"findIdentityByAccessToken" is not implemented.');
    }

    /**
     * Finds user by username
     *
     * @param string $username
     * @return static|null
     */
    public static function findByUsername($username)
    {
        return static::findOne(['username' => $username]);
    }

    /**
     * Finds user by password reset token
     *
     * @param string $token password reset token
     * @return static|null
     */
    public static function findByPasswordResetToken($token)
    {
        if (!static::isPasswordResetTokenValid($token)) {
            return null;
        }

        return static::findOne([
            'password_reset_token' => $token,
        ]);
    }

    /**
     * Finds out if password reset token is valid
     *
     * @param string $token password reset token
     * @return bool
     */
    public static function isPasswordResetTokenValid($token)
    {
        if (empty($token)) {
            return false;
        }

        $timestamp = (int) substr($token, strrpos($token, '_') + 1);
        $expire = \Yii::$app->params['user.passwordResetTokenExpire'];
        return $timestamp + $expire >= time();
    }

    /**
     * @inheritdoc
     */
    public function getId()
    {
        return $this->getPrimaryKey();
    }

    /**
     * @inheritdoc
     */
    public function getAuthKey()
    {
        return $this->auth_key;
    }

    /**
     * @inheritdoc
     */
    public function validateAuthKey($authKey)
    {
        return $this->getAuthKey() === $authKey;
    }

    /**
     * Validates password
     *
     * @param string $password password to validate
     * @return bool if password provided is valid for current user
     */
    public function validatePassword($password)
    {
        return \Yii::$app->security->validatePassword($password, $this->password);
    }

    /**
     * Generates password hash from password and sets it to the model
     *
     * @param string $password
     */
    public function setPassword($password)
    {
        $this->password = \Yii::$app->security->generatePasswordHash($password);
    }

    /**
     * Generates "remember me" authentication key
     */
    public function generateAuthKey()
    {
        $this->auth_key = \Yii::$app->security->generateRandomString();
    }

    /**
     * Generates new password reset token
     */
    public function generatePasswordResetToken()
    {
        $this->password_reset_token = \Yii::$app->security->generateRandomString() . '_' . time();
    }

    /**
     * @return string
     */
    public function getFullName()
    {
        /** @var UserProfileEntity $profile */
        $profile = $this->profile;
        if (!$profile) {
            return null;
        }
        return $profile->name . ' ' . $profile->last_name;
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProfile(): ActiveQuery
    {
        return $this->hasOne(UserProfileEntity::class, ['user_id' => 'id']);
    }

    /**
     * Removes password reset token
     */
    public function removePasswordResetToken()
    {
        $this->password_reset_token = null;
    }

    public function setLastLogin()
    {
        $this->last_login = time();
        $this->save(false, ['last_login']);
    }

    /**
     * @param boolean $value
     */
    public function setStatusOnline($value)
    {
        $this->status_online = (boolean) $value;
        $this->save(false, ['status_online']);
    }

    /**
     * @param $roleName
     * @return array
     */
    public static function findByRole($roleName): array
    {
        return \Yii::$app->authManager->getUserIdsByRole($roleName);
    }

    /**
     * Find user by phone number
     * @param $phoneNumber
     * @return null|static
     */
    public static function findByPhoneNumber($phoneNumber)
    {
        return static::findOne(['phone_number' => $phoneNumber]);
    }

    /**
     * Find user by email
     * @param $email
     * @return null|static
     */
    public static function findByEmail($email)
    {
        return static::findOne(['email' => $email]);
    }

    /**
     * Returns the number of managers
     * @return int
     */
    public static function getCountManagers(): int
    {
        return static::find()
            ->innerJoin('{{%auth_assignment}}', 'id = user_id')
            ->where(['item_name' => self::ROLE_MANAGER])
            ->count();
    }

    /**
     * @param bool $insert
     * @param array $changedAttributes
     */
    public function afterSave($insert, $changedAttributes)
    {
        if ($insert) {
            (new NotificationsEntity())->addNotify(
                NotificationsEntity::TYPE_NEW_USER,
                NotificationsEntity::getMessageForNewUser(),
                self::DEFAULT_ADMIN_ID,
                NotificationsEntity::getCustomDataForNewUser($this->phone_number)
            );
        }
        return parent::afterSave($insert, $changedAttributes);
    }

    /**
     * @param $status
     * @return string
     */
    public static function getInviteStatusValue($status): string
    {
        $statuses = static::getInviteStatuses();
        return $statuses[$status];
    }

    /**
     * @param integer $status
     * @return string
     */
    public static function getStatusOnlineValue($status): string
    {
        $statuses = static::getStatusOnlineLabels();
        return $statuses[$status];
    }

    /**
     * @param $status
     * @return string
     */
    public static function getAcceptInviteStatusValue($status): string
    {
        $statuses = static::getAcceptInviteLabels();
        return $statuses[$status];
    }

    /**
     * Returns ids list of all online managers
     * @return mixed
     */
    public static function getAllOnlineManagersIds()
    {
        $managersIds =  static::find()
            ->select('id')
            ->leftJoin('auth_assignment', 'auth_assignment.user_id = id')
            ->where(['user.status_online' => self::STATUS_ONLINE_YES])
            ->andWhere(['auth_assignment.item_name' => self::ROLE_MANAGER])
            ->column();

        return $managersIds ?? null;
    }
}

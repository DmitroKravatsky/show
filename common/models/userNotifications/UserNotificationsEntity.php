<?php

namespace common\models\userNotifications;

use common\behaviors\{
    ValidationExceptionFirstMessage
};
use common\models\{
    userNotifications\repositories\RestUserNotificationsRepository,
    userProfile\UserProfileEntity,
    user\User
};
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use Yii;

/**
 * Class UserNotificationsEntity
 * @package common\models\userNotifications
 * @property integer $id
 * @property integer $user_id
 * @property integer $notification_id
 * @property string  $is_read
 * @property integer $created_at
 * @property integer $updated_at
 *
 * @mixin ValidationExceptionFirstMessage
 *
 */
class UserNotificationsEntity extends ActiveRecord
{
    use RestUserNotificationsRepository;

    const STATUS_READ_YES = 1;
    const STATUS_READ_NO  = 0;

    /**
     * @return string
     */
    public static function tableName(): string
    {
        return '{{%user_notifications}}';
    }

    /**
     * @return array
     */
    public function rules(): array
    {
        return [
            [['id', 'user_id', 'notification_id', 'created_at'], 'integer'],
            [['is_read'], 'in',  'range' => [self::STATUS_READ_NO, self::STATUS_READ_YES]],
        ];
    }

    /**
     * @return array
     */
    public function attributeLabels(): array
    {
        return [
            'id'           => '#',
            'user_id'      => Yii::t('app', 'User id'),
            'notification_id' => Yii::t('app', 'Text'),
            'is_read'       => Yii::t('app', 'Read'),
            'created_at'   => Yii::t('app', 'Created At'),
            'updated_at'   => Yii::t('app', 'Updated At'),
        ];
    }

    /**
     * @return array
     */
    public function behaviors(): array
    {
        return [
            'TimestampBehavior'               => TimestampBehavior::class,
            'ValidationExceptionFirstMessage' => ValidationExceptionFirstMessage::class,
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRecipient()
    {
        return $this->hasOne(User::class, ['id' => 'recipient_id']);
    }

    /**
     * @return int
     */
    public static function getCountUnreadNotificationsByRecipient(): int
    {
       return (int) static::find()->where(['is_read' => 0, 'user_id' => Yii::$app->user->id])->count();
    }

    /**
     * Generates a message for a new user notification
     *
     * @return string
     */
    public static function getMessageForNewUser(): string
    {
        return 'A new user has been registered. Registration was conducted with a phone number {phone_number}.';
    }

    /**
     * Generates a custom data for a new user notification
     *
     * @param string $phoneNumber
     *
     * @return array
     */
    public static function getCustomDataForNewUser($phoneNumber): array
    {
        return ['phone_number' => $phoneNumber];
    }

    /**
     * Generates a message for a newly created bid. Status accepted is default
     *
     * @return string
     */
    public static function getMessageForNewBid(): string
    {
        return 'Your bid is accepted. Transfer to the card {sum} {currency} through the Wallet app. Recipient:Card/account {wallet}.';
    }

    /**
     * Generates a message for a newly created bid. Status accepted is default
     *
     * @param float $sum
     * @param string $currency
     * @param string $wallet
     *
     * @return array
     */
    public static function getCustomDataForNewBid($sum, $currency, $wallet): array
    {
        return [
            'sum' => $sum,
            'currency' => $currency,
            'wallet' => $wallet,
        ];
    }

    /**
     * Returns a list of users unread notifications
     * @return static[]
     */
    public static function getUnreadUserNotifications($limit)
    {
        return self::find()
            ->where(['is_read' => 0, 'user_id' => Yii::$app->user->id])
            ->with('notification')
            ->limit($limit)
            ->orderBy(['created_at' => SORT_DESC])
            ->all();
    }

    /**
     * Relates UserNotificationsEntity model with UserProfile model
     * @return \yii\db\ActiveQuery
     */
    public function getUserProfile()
    {
        return $this->hasOne(UserProfileEntity::class, ['user_id' => 'user_id']);
    }

    /**
     * Relates UserNotificationsEntity model with Notifications model
     * @return \yii\db\ActiveQuery
     */
    public function getNotification()
    {
        return $this->hasOne(NotificationsEntity::class, ['id' => 'notification_id']);
    }

    /**
     * Convert is_read status from bool to string
     * @return array
     */
    public static function getIsReadStatuses()
    {
        return [0 => Yii::t('app', 'No'), 1 => Yii::t('app', 'Yes')];
    }

    /**
     * @param $value
     * @return string
     */
    public static function getIsReadLabel($value)
    {
        return $value ? Yii::t('app', 'Yes') : Yii::t('app', 'No');
    }

}

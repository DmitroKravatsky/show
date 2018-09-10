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
            [['read'], 'enum', [0,1]],
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
     * Generates a message for paid bid by client
     *
     * @return string
     */
    public static function getMessageForClientPaid()
    {
        return 'Your payment of {sum} {currency} to wallet {wallet} is accepted.';
    }

    /**
     * Generates a custom data for paid bid by client
     *
     * @param float $sum
     * @param string $currency
     * @param string $wallet
     *
     * @return array
     */
    public static function getCustomDataForClientPaid($sum, $currency, $wallet)
    {
        return [
            'sum'      => $sum,
            'currency' => $currency,
            'wallet'   => $wallet,
        ];
    }

    /**
     * Generates a message for paid bid by client
     *
     * @return string
     */
    public static function getMessageForInProgress(): string
    {
        return 'Your bid number {bid_id} is now in progress.';
    }

    /**
     * Generates a message for paid bid by client
     *
     * @param integer $bidId
     *
     * @return array
     */
    public static function getCustomDataForInProgress($bidId): array
    {
        return ['bid_id' => $bidId];
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
    public function getIsReadStatuses()
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

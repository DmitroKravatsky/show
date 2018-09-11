<?php

namespace common\models\userNotifications;

use common\behaviors\{
    JsonBehavior,
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
 *
 * @mixin ValidationExceptionFirstMessage
 *
 * @property integer $id
 * @property integer $type
 * @property integer $recipient_id
 * @property string $text
 * @property string $custom_data
 * @property string $status
 * @property integer $created_at
 * @property integer $updated_at
 *
 * @property User $recipient
 * @property UserProfileEntity $recipientProfile
 */
class NotificationsEntity extends ActiveRecord
{
    use RestUserNotificationsRepository;

    const TYPE_NEW_USER        = 'new_user';
    const TYPE_NEW_BID         = 'new_bid';
    const TYPE_PAID_CLIENT     = 'paid_by_client';
    const TYPE_BID_IN_PROGRESS = 'bid_in_progress';
    const TYPE_BID_DONE        = 'bid_done';
    const TYPE_BID_REJECTED    = 'bid_rejected';

    /**
     * @return string
     */
    public static function tableName(): string
    {
        return '{{%notifications}}';
    }

    /**
     * @return array
     */
    public static function getTypeLabels(): array
    {
        return [
            self::TYPE_NEW_USER => Yii::t('app', 'New User'),
            self::TYPE_NEW_BID  => Yii::t('app', 'New Bid'),
        ];
    }

    /**
     * @return array
     */
    public function attributeLabels(): array
    {
        return [
            'id'           => '#',
            'text'         => Yii::t('app', 'Text'),
            'type'         => Yii::t('app', 'Notification Type'),
            'created_at'   => Yii::t('app', 'Created At'),
            'updated_at'   => Yii::t('app', 'Updated At'),
        ];
    }

    /**
     * @return array
     */
    public function rules(): array
    {
        return [
            [
                'type',
                'in',
                'range' => [
                    self::TYPE_NEW_USER, self::TYPE_NEW_BID, self::TYPE_PAID_CLIENT,
                    self::TYPE_BID_IN_PROGRESS, self::TYPE_BID_DONE, self::TYPE_BID_REJECTED
                ]
            ],
            ['text', 'string'],
            [['custom_data'], 'safe'],
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
            'json'                            => ['class' => JsonBehavior::class, 'attributes' => ['custom_data']],
        ];
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
        return 'User {full_name} has created new bid. Transfer to the card {sum} {currency} through the Wallet app. Recipient:Card/account {wallet}.';
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
    public static function getCustomDataForNewBid($fullName, $sum, $currency, $wallet): array
    {
        return [
            'full_name' => $fullName,
            'sum' => $sum,
            'currency' => $currency,
            'wallet' => $wallet,
        ];
    }


    /**
     * Generates a message for paid bid by client
     *
     * @return string
     */
    public static function getMessageForClientPaid()
    {
        return 'Client {full_name} has paid {sum} {currency} to wallet {wallet} .';
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
    public static function getCustomDataForClientPaid($fullName, $sum, $currency, $wallet)
    {
        return [
            'full_name' => $fullName,
            'sum'       => $sum,
            'currency'  => $currency,
            'wallet'    => $wallet,
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
     * Relates notifications with relation table user_notifications
     * @return \yii\db\ActiveQuery
     */
    public function getUserNotifications()
    {
        return $this->hasOne(UserNotificationsEntity::class, ['notification_id' => 'id']);
    }
}

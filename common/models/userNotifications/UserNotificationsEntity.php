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
use rest\modules\api\v1\authorization\models\RestUserEntity;
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
class UserNotificationsEntity extends ActiveRecord
{
    use RestUserNotificationsRepository;

    const STATUS_READ   = 'read';
    const STATUS_UNREAD = 'unread';

    const TYPE_NEW_USER        = 1;
    const TYPE_NEW_BID         = 2;
    const TYPE_PAID_CLIENT     = 3;
    const TYPE_BID_IN_PROGRESS = 4;
    const TYPE_BID_DONE        = 5;
    const TYPE_BID_REJECTED    = 6;

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
    public static function getStatusLabels(): array
    {
        return [
            self::STATUS_READ => Yii::t('app', 'Read'),
            self::STATUS_UNREAD => Yii::t('app', 'Unread'),
        ];
    }

    /**
     * @param string $status
     * @return string
     */
    public static function getStatusValue($status): string
    {
        $statuses = static::getStatusLabels();
        return $statuses[$status];
    }

    /**
     * @return array
     */
    public function attributeLabels(): array
    {
        return [
            'id'           => '#',
            'recipient_id' => Yii::t('app', 'Recipient'),
            'text'         => Yii::t('app', 'Text'),
            'status'       => Yii::t('app', 'Status'),
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
            [['recipient_id', 'type',], 'integer'],
            ['text', 'string'],
            ['status', 'in', 'range' => [self::STATUS_READ, self::STATUS_UNREAD]],
            [
                'recipient_id',
                'exist',
                'skipOnError'     => false,
                'targetClass'     => RestUserEntity::class,
                'targetAttribute' => ['recipient_id' => 'id'],
            ],
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
     * @return \yii\db\ActiveQuery
     */
    public function getRecipient()
    {
        return $this->hasOne(User::class, ['id' => 'recipient_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRecipientProfile()
    {
        return $this->hasOne(UserProfileEntity::class, ['user_id' => 'recipient_id']);
    }

    /**
     * @return int
     */
    public static function getCountUnreadNotificationsByRecipient(): int
    {
       return (int) static::find()->where(['status' => self::STATUS_UNREAD, 'recipient_id' => Yii::$app->user->id])->count();
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
            ->where(['status' => self::STATUS_UNREAD, 'recipient_id' => Yii::$app->user->id])
            ->with('userProfile')
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
        return $this->hasOne(UserProfileEntity::class, ['user_id' => 'recipient_id']);
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
}

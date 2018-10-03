<?php

namespace common\models\bid;

use common\models\{
    bid\repositories\RestBidRepository,
    bidHistory\BidHistory,
    user\User,
    userNotifications\NotificationsEntity,
    userProfile\UserProfileEntity
};
use yii\{behaviors\TimestampBehavior, db\ActiveRecord, helpers\ArrayHelper};
use Yii;
use rest\behaviors\ValidationExceptionFirstMessage;
use borales\extensions\phoneInput\PhoneInputValidator;
use common\models\paymentSystem\PaymentSystem;

/**
 * Class BidEntity
 * @package common\models\bid
 *
 * @mixin ValidationExceptionFirstMessage
 *
 * @property integer $id
 * @property integer $created_by
 * @property integer $from_payment_system_id
 * @property integer $to_payment_system_id
 * @property string $name
 * @property string $last_name
 * @property string $phone_number
 * @property string $email
 * @property string $status
 * @property string $from_wallet
 * @property string $to_wallet
 * @property float $from_sum
 * @property float $to_sum
 * @property int $processed
 * @property int $processed_by
 * @property integer $in_progress_by_manager
 * @property integer $created_at
 * @property integer $updated_at
 *
 * @property User $author
 * @property User $processedBy
 * @property UserProfileEntity $processedByProfile
 * @property User $inProgressByManager
 * @property UserProfileEntity $bidOwnerProfile
 * @property BidHistory[] $bidHistories
 * @property PaymentSystem $fromPaymentSystem
 * @property PaymentSystem $toPaymentSystem
 */
class BidEntity extends ActiveRecord
{
    use RestBidRepository;

    const SCENARIO_CREATE            = 'create';
    const SCENARIO_UPDATE            = 'update';
    const SCENARIO_UPDATE_BID_STATUS = 'update-bid-status';

    const STATUS_NEW             = 'new';
    const STATUS_PAID_BY_CLIENT  = 'paid_by_client';
    const STATUS_IN_PROGRESS     = 'in_progress';
    const STATUS_PAID_BY_US_DONE = 'paid_by_us_done';
    const STATUS_REJECTED        = 'rejected';

    const PROCESSED_YES = 1;
    const PROCESSED_NO  = 0;

    const SORT_WEEK    = 'week';
    const SORT_MONTH   = 'month';

    const SECONDS_IN_WEEK  = 3600 * 24 * 7;
    const SECONDS_IN_MONTH = 3600 * 24 * 30;

    /**
     * @var bool
     */
    public $terms_confirm = false;
    public $name;
    public $last_name;
    public $full_name;
    public $email;
    public $phone_number;

    /**
     * @return string
     */
    public static function tableName(): string
    {
        return '{{%bid}}';
    }

    /**
     * @return array
     */
    public static function getProcessedStatuses(): array
    {
        return [
            self::PROCESSED_NO => Yii::t('app', 'No'),
            self::PROCESSED_YES => Yii::t('app', 'Yes'),
        ];
    }

    /**
     * @param $status
     * @return string
     */
    public static function getProcessedStatusValue($status): string
    {
        $statuses = static::getProcessedStatuses();
        return $statuses[$status];
    }

    /**
     * @return array
     */
    public static function statusLabels(): array
    {
        return [
            self::STATUS_NEW             => Yii::t('app', 'New'),
            self::STATUS_PAID_BY_CLIENT  => Yii::t('app', 'Paid by client'),
            self::STATUS_IN_PROGRESS     => Yii::t('app', 'In progress'),
            self::STATUS_PAID_BY_US_DONE => Yii::t('app', 'Paid by us'),
            self::STATUS_REJECTED        => Yii::t('app', 'Rejected'),
        ];
    }

    /**
     * @return array
     */
    public static function getManagerAllowedStatuses(): array
    {
        return [
            self::STATUS_IN_PROGRESS     => Yii::t('app', 'In progress'),
            self::STATUS_PAID_BY_US_DONE => Yii::t('app', 'Paid by us'),
            self::STATUS_REJECTED        => Yii::t('app', 'Rejected'),
        ];
    }

    /**
     * @param string $status
     * @return array
     */
    public static function getManagerAllowedStatusesWithOutCurrentStatus($status): array
    {
        $statuses = static::getManagerAllowedStatuses();
        ArrayHelper::remove($statuses, $status);
        return $statuses;
    }

    /**
     * @return array
     */
    public function attributeLabels(): array
    {
        return [
            'id'                     => '#',
            'created_by'             => Yii::t('app', 'Created By'),
            'from_sum'               => Yii::t('app', 'Sum'),
            'to_sum'                 => Yii::t('app', 'To Sum'),
            'from_wallet'            => Yii::t('app', 'From Wallet'),
            'to_wallet'              => Yii::t('app', 'To Wallet'),
            'from_payment_system_id' => Yii::t('app', 'From Payment System'),
            'to_payment_system_id'   => Yii::t('app', 'To Payment System'),
            'name'                   => Yii::t('app', 'First Name'),
            'last_name'              => Yii::t('app', 'Last Name'),
            'email'                  => Yii::t('app', 'Email'),
            'phone_number'           => Yii::t('app', 'Phone Number'),
            'status'                 => Yii::t('app', 'Status'),
            'terms_confirm'          => Yii::t('app', 'Terms And Conditions'),
            'processed'              => Yii::t('app', 'Processed'),
            'processed_by'           => Yii::t('app', 'Processed By'),
            'in_progress_by_manager' => Yii::t('app', 'In Progress By Manager'),
            'created_at'             => Yii::t('app', 'Created At'),
            'updated_at'             => Yii::t('app', 'Updated At'),
        ];
    }

    /**
     * @return array
     */
    public function scenarios(): array
    {
        $scenarios = parent::scenarios();

        $scenarios[self::SCENARIO_CREATE] = [
            'created_by', 'status', 'from_wallet', 'to_wallet', 'name', 'last_name', 'email', 'phone_number',
            'from_sum', 'to_sum', 'terms_confirm', 'from_payment_system_id', 'to_payment_system_id',
        ];

        $scenarios[self::SCENARIO_UPDATE] = [
            'created_by', 'status', 'from_wallet', 'to_wallet', 'name', 'last_name', 'email', 'phone_number',
            'from_sum', 'to_sum', 'from_payment_system_id', 'to_payment_system_id',
        ];
        $scenarios[self::SCENARIO_UPDATE_BID_STATUS] = ['status', 'processed', 'processed_by'];

        return $scenarios;
    }

    /**
     * @return array
     */
    public function rules(): array
    {
        return [
            [['id', 'created_by', 'processed', 'processedBy', 'in_progress_by_manager',], 'integer'],
            ['created_by', 'default', 'value' => \Yii::$app->user->id],
            [
                'created_by',
                'exist',
                'skipOnError'     => false,
                'targetClass'     => User::class,
                'targetAttribute' => ['created_by' => 'id'],
            ],
            ['status', 'in', 'range' =>
                [
                    self::STATUS_NEW, self::STATUS_REJECTED, self::STATUS_IN_PROGRESS,
                    self::STATUS_PAID_BY_US_DONE, self::STATUS_PAID_BY_CLIENT,
                ]
            ],
            [
                [
                    'from_wallet', 'to_wallet', 'name', 'last_name', 'email',
                    'phone_number', 'from_sum', 'to_sum', 'from_payment_system_id', 'to_payment_system_id'
                ],
                'required'
            ],
            [['email'], 'string', 'max' => 255],
            [['from_wallet', 'to_wallet'], 'string', 'max' => 32, 'min' => 12],
            [['name', 'last_name', 'phone_number'], 'string', 'max' => 20],
            [['name', 'last_name'], 'string', 'min' => 2],
            ['email', 'email'],
            [['from_sum', 'to_sum'], 'double', 'min' => 10],
            [['created_at', 'updated_at'], 'safe'],
            ['terms_confirm', 'boolean', 'on' => self::SCENARIO_CREATE],
            [
                'terms_confirm',
                'required',
                'on'            => self::SCENARIO_CREATE,
                'requiredValue' => 1,
                'message'       => \Yii::t('app', 'Вы должны принять "Пользовательские соглашения"')
            ],
            [['processed_by', 'in_progress_by_manager'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['processed_by' => 'id']],
            [['in_progress_by_manager', 'in_progress_by_manager'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['in_progress_by_manager' => 'id']],
            [['processed_by'], 'required', 'when' => function (self $bid) {
                return $bid->processed;
            }],
            [['phone_number'], PhoneInputValidator::class],
            [
                ['from_payment_system_id'],
                'exist',
                'skipOnError' => true,
                'targetClass' => PaymentSystem::class,
                'targetAttribute' => ['from_payment_system_id' => 'id']
            ],
            [
                ['to_payment_system_id'],
                'exist',
                'skipOnError' => true,
                'targetClass' => PaymentSystem::class,
                'targetAttribute' => ['to_payment_system_id' => 'id']
            ],

        ];
    }

    /**
     * @return array
     */
    public function behaviors(): array
    {
        return [
            TimestampBehavior::class,
            ValidationExceptionFirstMessage::class,
        ];
    }

    /**
     * @param $insert
     * @return bool
     */
    public function beforeSave($insert)
    {
        return parent::beforeSave($insert);
    }

    public function afterSave($insert, $changedAttributes)
    {
        $bidHistory = new BidHistory();
        $bidHistory->bid_id = $this->id;
        $bidHistory->status = $this->status;

        $bidOwnerFullName = $this->bidOwnerProfile ? $this->bidOwnerProfile->UserFullName : null;

        if ($insert) {
            $this->sendEmailToManagers($this);
            (new NotificationsEntity())->addNotify(
                NotificationsEntity::TYPE_NEW_BID,
                NotificationsEntity::getMessageForNewBid(),
                User::getAllOnlineManagersIds(),
                NotificationsEntity::getCustomDataForNewBid(
                    $bidOwnerFullName,
                    $this->to_sum,
                    $this->toPaymentSystem->currency,
                    $this->to_wallet
                )
            );
        } elseif (!$insert) {
            if ($this->status === self::STATUS_IN_PROGRESS) {
                $bidHistory->in_progress_by_manager = Yii::$app->user->id;
            } else {
                $bidHistory->processed_by = Yii::$app->user->id;
            }
        }
        $bidHistory->save(false);

        if ($this->status === BidEntity::STATUS_PAID_BY_CLIENT) {
            (new NotificationsEntity())->addNotify(
                NotificationsEntity::TYPE_PAID_CLIENT,
                NotificationsEntity::getMessageForClientPaid(),
                User::getAllOnlineManagersIds(),
                NotificationsEntity::getCustomDataForClientPaid(
                    $bidOwnerFullName,
                    $this->from_sum,
                    $this->fromPaymentSystem->currency,
                    $this->to_wallet
                )
            );
        }
        if ($this->status === BidEntity::STATUS_IN_PROGRESS) {
            (new NotificationsEntity())->addNotify(
                NotificationsEntity::TYPE_BID_IN_PROGRESS,
                NotificationsEntity::getMessageForInProgress(),
                $this->created_by,
                NotificationsEntity::getCustomDataForInProgress($this->id)
            );
        }

        if ($this->status == self::STATUS_PAID_BY_US_DONE) {
            (new NotificationsEntity())->addNotify(
                NotificationsEntity::TYPE_BID_DONE,
                NotificationsEntity::getMessageForDoneBid(),
                $this->created_by,
                NotificationsEntity::getCustomDataForDoneBid($this->to_sum, $this->toPaymentSystem->currency, $this->to_wallet)
            );
        } elseif ($this->status == self::STATUS_REJECTED) {
            (new NotificationsEntity())->addNotify(
                NotificationsEntity::TYPE_BID_REJECTED,
                NotificationsEntity::getMessageForRejectedBid(),
                $this->created_by,
                NotificationsEntity::getCustomDataForRejectedBid($this->to_sum, $this->toPaymentSystem->currency, $this->to_wallet)
            );
        }

        return parent::afterSave($insert, $changedAttributes);
    }

    /**
     * @param $status
     * @return string
     */
    public static function getStatusValue($status): string
    {
        $statuses = static::statusLabels();
        return $statuses[$status];
    }

    /**
     * @return bool
     */
    public function toggleProcessed(): bool
    {
        $this->processed = !$this->processed;
        return $this->save(false);
    }

    /**
     * Returns bid's author
     * @return \yii\db\ActiveQuery
     */
    public function getAuthor()
    {
        return $this->hasOne(User::class, ['id' => 'created_by']);
    }

    /**
     * Returns bid's author
     * @return \yii\db\ActiveQuery
     */
    public function getProcessedByProfile()
    {
        return $this->hasOne(UserProfileEntity::class, ['user_id' => 'processed_by']);
    }

    /**
     * Returns bid's author
     * @return \yii\db\ActiveQuery
     */
    public function getInProgressByManager()
    {
        return $this->hasOne(User::class, ['id' => 'in_progress_by_manager']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPerfomer()
    {
        if ($this->processed_by !== null) {
            return $this->hasOne(User::class, ['id' => 'processed_by']);
        }
        return null;
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBidHistories()
    {
        return $this->hasMany(BidHistory::class, ['bid_id' => 'id']);
    }

    public static function getProcessedStatusList()
    {
        return [
            self::PROCESSED_NO => Yii::t('app', 'No'),
            self::PROCESSED_YES => Yii::t('app', 'Yes')
        ];
    }

    public function getProcessedStatus()
    {
        $statuses = static::getProcessedStatusList();
        return $statuses[$this->processed];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getManagerProfile()
    {
        return $this->hasOne(UserProfileEntity::class, ['user_id' => 'processed_by']);
    }

    /**
     * @param string $status
     * @return bool
     */
    public static function canUpdateStatus($status): bool
    {
        $statuses = [self::STATUS_PAID_BY_US_DONE, self::STATUS_REJECTED];
        if (!Yii::$app->user->can(User::ROLE_ADMIN) && in_array($status, $statuses)) {
            return false;
        }
        return true;
    }

    /**
     * Relates BidEntity with UserProfile
     * @return \yii\db\ActiveQuery
     */
    public function getBidOwnerProfile()
    {
        return $this->hasOne(UserProfileEntity::class, ['user_id' => 'created_by']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getFromPaymentSystem()
    {
        return $this->hasOne(PaymentSystem::class, ['id' => 'from_payment_system_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getToPaymentSystem()
    {
        return $this->hasOne(PaymentSystem::class, ['id' => 'to_payment_system_id']);
    }

    /**
     * @param string $status
     * @return bool
     */
    public static function isExcepted($status): bool
    {
        $exceptedStatuses = [self::STATUS_NEW, self::STATUS_IN_PROGRESS, self::STATUS_PAID_BY_CLIENT];
        return in_array($status, $exceptedStatuses);
    }
}

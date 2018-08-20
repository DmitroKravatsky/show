<?php

namespace common\models\bid;

use common\models\{
    bid\repositories\RestBidRepository,
    bidHistory\BidHistory,
    user\User,
    userNotifications\UserNotificationsEntity
};
use rest\behaviors\ValidationExceptionFirstMessage;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use Yii;

/**
 * Class BidEntity
 * @package common\models\bid
 *
 * @mixin ValidationExceptionFirstMessage
 *
 * @property integer $id
 * @property integer $created_by
 * @property string $name
 * @property string $last_name
 * @property string $phone_number
 * @property string $email
 * @property string $status
 * @property string $from_payment_system
 * @property string $to_payment_system
 * @property string $from_wallet
 * @property string $to_wallet
 * @property string $from_currency
 * @property string $to_currency
 * @property float $from_sum
 * @property float $to_sum
 * @property int $processed
 * @property int $processed_by
 * @property integer $created_at
 * @property integer $updated_at
 *
 * @property User $author
 * @property User $processedBy
 * @property BidHistory[] $bidHistories
 */
class BidEntity extends ActiveRecord
{
    use RestBidRepository;

    const SCENARIO_CREATE = 'create';
    const SCENARIO_UPDATE = 'update';
    const SCENARIO_UPDATE_BID_STATUS = 'update-bid-status';

    const YANDEX_MONEY = 'yandex_money';
    const WEB_MONEY    = 'web_money';
    const TINCOFF      = 'tincoff';
    const PRIVAT24     = 'privat24';
    const SBERBANK     = 'sberbank';
    const QIWI         = 'qiwi';

    const USD = 'usd';
    const UAH = 'uah';
    const RUB = 'rub';
    const EUR = 'eur';

    const STATUS_NEW            = 'new';
    const STATUS_PAID_BY_CLIENT = 'paid_by_client';
    const STATUS_IN_PROGRESS    = 'in_progress';
    const STATUS_PAID_BY_US     = 'paid_by_us';
    const STATUS_DONE           = 'done';
    const STATUS_REJECTED       = 'rejected';

    const PROCESSED_YES = 1;
    const PROCESSED_NO  = 0;

    /**
     * @var bool
     */
    public $terms_confirm = false;

    public $full_name;

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
            self::STATUS_NEW            => Yii::t('app', 'New'),
            self::STATUS_PAID_BY_CLIENT => Yii::t('app', 'Paid by client'),
            self::STATUS_IN_PROGRESS    => Yii::t('app', 'In progress'),
            self::STATUS_PAID_BY_US     => Yii::t('app', 'Paid by us'),
            self::STATUS_DONE           => Yii::t('app', 'Done'),
            self::STATUS_REJECTED       => Yii::t('app', 'Rejected'),
        ];
    }

    /**
     * Returns the name of payment systems
     * @return array
     */
    public static function paymentSystemLabels(): array
    {
        return [
            self::YANDEX_MONEY => Yii::t('app', 'Yandex Money'),
            self::WEB_MONEY => Yii::t('app', 'Web Money'),
            self::TINCOFF => Yii::t('app', 'Tincoff'),
            self::PRIVAT24 => Yii::t('app', 'Privat24'),
            self::SBERBANK => Yii::t('app', 'Sberbank'),
            self::QIWI => Yii::t('app', 'Qiwi'),
        ];
    }

    /**
     * Returns the name of the payment system by system value
     * @param string $system
     * @return string
     */
    public static function getPaymentSystemValue($system): string
    {
        $systems = static::paymentSystemLabels();
        return $systems[$system];
    }

    /**
     * @return array
     */
    public function attributeLabels(): array
    {
        return [
            'id'                  => '#',
            'created_by'          => Yii::t('app', 'Created By'),
            'from_sum'            => Yii::t('app', 'Sum'),
            'to_sum'              => Yii::t('app', 'To Sum'),
            'from_wallet'         => Yii::t('app', 'From Wallet'),
            'to_wallet'           => Yii::t('app', 'To Wallet'),
            'from_payment_system' => Yii::t('app', 'From Payment System'),
            'to_payment_system'   => Yii::t('app', 'To Payment System'),
            'name'                => Yii::t('app', 'First Name'),
            'last_name'           => Yii::t('app', 'Last Name'),
            'email'               => Yii::t('app', 'Email'),
            'phone_number'        => Yii::t('app', 'Phone Number'),
            'status'              => Yii::t('app', 'Status'),
            'terms_confirm'       => Yii::t('app', 'Terms And Conditions'),
            'processed'           => Yii::t('app', 'Processed'),
            'processed_by'        => Yii::t('app', 'Processed By'),
            'created_at'          => Yii::t('app', 'Created At'),
            'updated_at'          => Yii::t('app', 'Updated At'),
        ];
    }

    /**
     * @return array
     */
    public function scenarios(): array
    {
        $scenarios = parent::scenarios();

        $scenarios[self::SCENARIO_CREATE] = [
            'created_by', 'status', 'from_wallet', 'to_wallet', 'from_currency', 'to_currency', 'name', 'last_name',
            'email', 'phone_number', 'from_sum', 'to_sum', 'terms_confirm', 'from_payment_system', 'to_payment_system',
        ];

        $scenarios[self::SCENARIO_UPDATE] = [
            'created_by', 'status', 'from_wallet', 'to_wallet', 'from_currency', 'to_currency', 'name', 'last_name',
            'email', 'phone_number', 'from_sum', 'to_sum', 'from_payment_system', 'to_payment_system',
        ];
        $scenarios[self::SCENARIO_UPDATE_BID_STATUS] = ['status'];

        return $scenarios;
    }

    /**
     * @return array
     */
    public function rules(): array
    {
        return [
            [['id', 'created_by', 'processed', 'processedBy',], 'integer'],
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
                    self::STATUS_NEW, self::STATUS_REJECTED, self::STATUS_DONE,
                    self::STATUS_PAID_BY_US, self::STATUS_PAID_BY_CLIENT,
                ]
            ],
            [
                [
                    'from_wallet', 'to_wallet', 'from_currency', 'to_currency', 'name', 'last_name', 'email',
                    'phone_number', 'from_sum', 'to_sum', 'from_payment_system', 'to_payment_system'
                ],
                'required'
            ],
            [['email'], 'string', 'max' => 255],
            [['from_wallet', 'to_wallet'], 'string', 'max' => 32],
            [['name', 'last_name', 'phone_number'], 'string', 'max' => 20],
            [
                ['from_payment_system', 'to_payment_system'],
                'in',
                'range' => [self::PRIVAT24, self::SBERBANK, self::TINCOFF, self::WEB_MONEY, self::YANDEX_MONEY, self::QIWI]
            ],
            [['from_currency', 'to_currency'], 'in', 'range' => [self::RUB, self::UAH, self::USD, self::EUR]],
            ['email', 'email'],
            [['from_sum', 'to_sum'], 'double'],
            [['created_at', 'updated_at'], 'safe'],
            ['terms_confirm', 'boolean', 'on' => self::SCENARIO_CREATE],
            [
                'terms_confirm',
                'required',
                'on'            => self::SCENARIO_CREATE,
                'requiredValue' => 1,
                'message'       => \Yii::t('app', 'Вы должны принять "Пользовательские соглашения"')
            ],
            [['processed_by'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['processed_by' => 'id']],
            [['processed_by'], 'required', 'when' => function (self $bid) {
                return $bid->processed;
            }],

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
        if ($this->status == self::STATUS_DONE) {
            (new UserNotificationsEntity)->addNotify(
                UserNotificationsEntity::getMessageForDoneBid([
                    'created_by'  => $this->created_by,
                    'to_sum'      => $this->to_sum,
                    'to_currency' => $this->to_currency,
                    'to_wallet'   => $this->to_wallet
                ]),
                $this->created_by
            );
        } elseif ($this->status == self::STATUS_REJECTED) {
            (new UserNotificationsEntity)->addNotify(
                UserNotificationsEntity::getMessageForRejectedBid([
                    'created_by'  => $this->created_by,
                    'to_sum'      => $this->to_sum,
                    'to_currency' => $this->to_currency,
                    'to_wallet'   => $this->to_wallet
                ]),
                $this->created_by
            );
        }

        return parent::beforeSave($insert);
    }

    public function afterSave($insert, $changedAttributes)
    {
        if ($insert) {
            $this->sendEmailToManagers($this);
        }

        if ($this->status === BidEntity::STATUS_PAID_BY_CLIENT) {
            (new UserNotificationsEntity())->addNotify(
                UserNotificationsEntity::getMessageForClientPaid([
                    'from_currency' => $this->from_currency,
                    'from_sum'      => $this->from_sum,
                    'to_wallet'     => $this->to_wallet
                ]),
                $this->created_by
            );
        }
        if ($this->status === BidEntity::STATUS_IN_PROGRESS) {
            (new UserNotificationsEntity())->addNotify(
                UserNotificationsEntity::getMessageForInProgress([
                    'bid_id' => $this->id,
                ]),
                $this->created_by
            );
        }

        if ($insert && $this->created_by === Yii::$app->user->id) {
            (new UserNotificationsEntity)->addNotify(
                UserNotificationsEntity::getMessageForNewBid([
                    'created_by'  => $this->created_by,
                    'to_sum'      => $this->to_sum,
                    'to_currency' => $this->to_currency,
                    'to_wallet'   => $this->to_wallet
                ]),
                $this->created_by
            );
        }

        $bidHistory = new BidHistory();
        $bidHistory->bid_id = $this->id;
        $bidHistory->status = $this->status;

        $bidHistory->save(false);

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
     * Returns all available values of bid status
     * @return array
     */
    public static function getAllAvailableStatuses(): array
    {
        return [
            BidEntity::STATUS_NEW             => BidEntity::STATUS_NEW,
            BidEntity::STATUS_PAID_BY_CLIENT  => BidEntity::STATUS_PAID_BY_CLIENT,
            BidEntity::STATUS_IN_PROGRESS     => BidEntity::STATUS_IN_PROGRESS,
            BidEntity::STATUS_PAID_BY_US      => BidEntity::STATUS_PAID_BY_US,
            BidEntity::STATUS_DONE            => BidEntity::STATUS_DONE,
            BidEntity::STATUS_REJECTED        => BidEntity::STATUS_REJECTED,
        ];
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
     * @return \yii\db\ActiveQuery
     */
    public function getProcessedBy()
    {
        return $this->hasOne(User::class, ['id' => 'processed_by']);
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
        return [self::PROCESSED_NO => Yii::t('app', 'no'), self::PROCESSED_YES => Yii::t('app', 'yes')];
    }

    public function getProcessedStatus()
    {
        $statuses = static::getProcessedStatusList();
        return $statuses[$this->processed];
    }
}

<?php

namespace common\models\bid;

use common\models\{
    bid\repositories\RestBidRepository, user\User, userNotifications\UserNotificationsEntity
};
use rest\behaviors\ValidationExceptionFirstMessage;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

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
 * @property integer $created_at
 * @property integer $updated_at
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

    const STATUS_ACCEPTED = 'accepted';
    const STATUS_PAID     = 'paid';
    const STATUS_DONE     = 'done';
    const STATUS_REJECTED = 'rejected';

    /**
     * @var bool
     */
    public $terms_confirm = false;

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
    public function attributeLabels(): array
    {
        return [
            'id'                  => '#',
            'created_by'          => 'Автор',
            'from_sum'            => 'Сумма',
            'to_sum'              => 'Сумма с учетом комиссии',
            'from_wallet'         => 'Номер вашего кошелька',
            'to_wallet'           => 'Номер карты',
            'from_payment_system' => 'Вы отдаёте',
            'to_payment_system'   => 'Вы получаете',
            'name'                => 'Имя',
            'last_name'           => 'Фамилия',
            'email'               => 'Email',
            'phone_number'        => 'Номер телефона',
            'status'              => 'Статус',
            'terms_confirm'       => 'Пользовательское соглашение',
            'created_at'          => 'Дата создания',
            'updated_at'          => 'Дата изменения',
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
            [['id', 'created_by'], 'integer'],
            ['created_by', 'default', 'value' => \Yii::$app->user->id],
            [
                'created_by',
                'exist',
                'skipOnError'     => false,
                'targetClass'     => User::class,
                'targetAttribute' => ['created_by' => 'id'],
            ],
            ['status', 'in', 'range' => [self::STATUS_ACCEPTED, self::STATUS_REJECTED, self::STATUS_DONE, self::STATUS_PAID]],
            [
                [
                    'from_wallet', 'to_wallet', 'from_currency', 'to_currency', 'name', 'last_name', 'email',
                    'phone_number', 'from_sum', 'to_sum', 'from_payment_system', 'to_payment_system'
                ],
                'required'
            ],
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
}
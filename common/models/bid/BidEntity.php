<?php

namespace common\models\bid;

use common\models\bid\repositories\RestBidRepository;
use common\models\User;
use yii\behaviors\TimestampBehavior;

/**
 * Class BidEntity
 * @package common\models\bid
 *
 * @property integer $id
 * @property integer $created_by
 * @property double $from_sum
 * @property double $to_sum
 * @property string $from_wallet
 * @property string $to_wallet
 * @property string $from_currency
 * @property string $to_currency
 * @property string $name
 * @property string $last_name
 * @property string $email
 * @property string $phone_number
 * @property integer $created_at
 * @property integer $updated_at
 */
class BidEntity extends \yii\db\ActiveRecord
{
    use RestBidRepository;

    const SCENARIO_CREATE = 'create';
    const SCENARIO_UPDATE = 'update';

    const YANDEX_MONEY = 'yandex_money';
    const WEB_MONEY = 'web_money';
    const TINCOFF = 'tincoff';
    const PRIVAT24 = 'privat24';
    const SBERBANK = 'sberbank';

    const USD = 'usd';
    const UAH = 'uah';
    const RUB = 'rub';

    const STATUS_VERIFIED = 'verified';
    const STATUS_ACCEPTED = 'accepted';
    const STATUS_REJECTED = 'rejected';

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
            'id' => '#',
            'created_by' => 'Автор',
            'from_sum' => 'Сумма',
            'to_sum' => 'Сумма',
            'from_wallet' => 'Со счета',
            'to_wallet' => 'На счет',
            'from_currency' => 'Валюта',
            'to_currency' => 'Валюта',
            'name' => 'Имя',
            'last_name' => 'Фамилия',
            'email' => 'Email',
            'phone_number' => 'Номер телефона',
            'status' => 'Статус',
            'created_at' => 'Дата создания',
            'updated_at' => 'Дата изменения'
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
            'email', 'phone_number', 'from_sum', 'to_sum'
        ];

        $scenarios[self::SCENARIO_UPDATE] = [
            'created_by', 'status', 'from_wallet', 'to_wallet', 'from_currency', 'to_currency', 'name', 'last_name',
            'email', 'phone_number', 'from_sum', 'to_sum'
        ];

        return $scenarios;
    }

    /**
     * @return array
     */
    public function rules(): array
    {
        return [
            [['id', 'created_by'], 'integer'],
            [
                'created_by', 'default', 'value' => \Yii::$app->user->id
            ],
            [
                ['created_by'], 'exist',
                'skipOnError'     => false,
                'targetClass'     => User::className(),
                'targetAttribute' => ['created_by' => 'id'],
            ],
            ['status', 'in', 'range' => [self::STATUS_ACCEPTED, self::STATUS_REJECTED, self::STATUS_VERIFIED]],

            [
                ['from_wallet', 'to_wallet', 'from_currency', 'to_currency', 'name', 'last_name', 'email', 'phone_number'],
                'string'
            ],
            [
                [
                    'from_wallet', 'to_wallet', 'from_currency', 'to_currency', 'name', 'last_name', 'email',
                    'phone_number', 'from_sum', 'to_sum'
                ],
                'required'
            ],
            [
                ['from_wallet', 'to_wallet'],
                'in',
                'range' => [self::PRIVAT24, self::SBERBANK, self::TINCOFF, self::WEB_MONEY, self::YANDEX_MONEY]
            ],
            [
                ['from_currency', 'to_currency'], 'in', 'range' => [self::RUB, self::UAH, self::USD]
            ],
            ['email', 'email'],
            ['email', 'unique'],
            [['from_sum', 'to_sum'], 'double'],
            [['created_at', 'updated_at'], 'safe']
        ];
    }

    /**
     * @return array
     */
    public function behaviors(): array
    {
        return [
            TimestampBehavior::className(),
        ];
    }


}
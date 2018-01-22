<?php

namespace common\models\bid;

use common\models\bid\repositories\RestBidRepository;
use common\models\User;
use yii\behaviors\TimestampBehavior;
use Yii;

/**
 * Class BidEntity
 * @package common\models\bid
 *
 * @property integer $id
 * @property integer $created_by
 * @property integer $from_wallet_number
 * @property integer $to_wallet_number
 * @property integer $from_card_number
 * @property integer $to_card_number
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
    const QIWI = 'qiwi';

    const USD = 'usd';
    const UAH = 'uah';
    const RUB = 'rub';
    const EUR = 'eur';

    const STATUS_ACCEPTED = 'accepted';
    const STATUS_PAID = 'paid';
    const STATUS_DONE = 'done';
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
            'id' => '#',
            'created_by' => 'Автор',
            'from_sum' => 'Сумма',
            'to_sum' => 'Сумма',
            'from_wallet' => 'Вы отдаёте',
            'to_wallet' => 'Вы получаете',
            'from_currency' => 'Валюта',
            'to_currency' => 'Валюта',
            'name' => 'Имя',
            'last_name' => 'Фамилия',
            'email' => 'Email',
            'phone_number' => 'Номер телефона',
            'status' => 'Статус',
            'from_wallet_number' => 'Номер кошелька отправки',
            'to_wallet_number' => 'Номер кошелька получения',
            'from_card_number' => 'Номер карты отправки',
            'to_card_number' => 'Номер карты получения',
            'terms_confirm' => 'Пользовательское соглашение',
            'created_at' => 'Дата создания',
            'updated_at' => 'Дата изменения',
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
            'email', 'phone_number', 'from_sum', 'to_sum', 'from_wallet_number', 'to_wallet_number', 'from_card_number',
            'to_card_number', 'terms_confirm'
        ];

        $scenarios[self::SCENARIO_UPDATE] = [
            'created_by', 'status', 'from_wallet', 'to_wallet', 'from_currency', 'to_currency', 'name', 'last_name',
            'email', 'phone_number', 'from_sum', 'to_sum', 'from_wallet_number', 'to_wallet_number', 'from_card_number',
            'to_card_number',
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
            ['status', 'in', 'range' => [self::STATUS_ACCEPTED, self::STATUS_REJECTED, self::STATUS_PAID, self::STATUS_DONE]],
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
                [
                    'name', 'last_name', 'phone_number', 'from_wallet_number', 'to_wallet_number', 'from_card_number',
                    'to_card_number'
                ],
                'string',
                'max' => 20
            ],
            [
                ['from_wallet', 'to_wallet'],
                'in',
                'range' => [self::PRIVAT24, self::SBERBANK, self::TINCOFF, self::WEB_MONEY, self::YANDEX_MONEY, self::QIWI]
            ],
            [
                ['from_currency', 'to_currency'], 'in', 'range' => [self::RUB, self::UAH, self::USD, self::EUR]
            ],
            ['email', 'email'],
            [['from_sum'], 'double'],
            [['created_at', 'updated_at', 'to_sum'], 'safe'],
            [
                'from_wallet_number',
                'required',
                'when' => function ($model) { return empty($model->from_card_number); },
                'message' => 'Необходимо заполнить одно из полей "Номер кошелька отправки" или "Номер карты отправки"'
            ],
            [
                'from_card_number',
                'required',
                'when' => function ($model) { return empty($model->from_wallet_number); },
                'message' => 'Необходимо заполнить одно из полей "Номер кошелька отправки" или "Номер карты отправки"'
            ],
            [
                'to_wallet_number',
                'required',
                'when' => function ($model) { return empty($model->to_card_number); },
                'message' => 'Необходимо заполнить одно из полей "Номер кошелька получения" или "Номер карты получения"'
            ],
            [
                'to_card_number',
                'required',
                'when' => function ($model) { return empty($model->to_wallet_number); },
                'message' => 'Необходимо заполнить одно из полей "Номер кошелька получения" или "Номер карты получения"'
            ],
            ['terms_confirm', 'boolean', 'on' => self::SCENARIO_CREATE],
            [
                'terms_confirm',
                'required',
                'on'            => self::SCENARIO_CREATE,
                'requiredValue' => 1,
                'message'       => Yii::t('app', 'Вы должны принять "Пользовательские соглашения"')
            ],

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
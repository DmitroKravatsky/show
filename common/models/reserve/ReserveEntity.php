<?php

namespace common\models\reserve;

use common\models\reserve\repositories\RestReserveRepository;
use rest\behaviors\ValidationExceptionFirstMessage;
use yii\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;
use Yii;

/**
 * Class ReserveEntity
 * @package common\models\reserve
 *
 * @mixin ValidationExceptionFirstMessage
 *
 * @property integer $id
 * @property string $payment_system
 * @property string $currency
 * @property float $sum
 * @property integer $created_at
 * @property integer $updated_at
 */
class ReserveEntity extends ActiveRecord
{
    use RestReserveRepository;

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

    /**
     * @return array
     */
    public static function paymentSystemLabels(): array
    {
        return [
            self::YANDEX_MONEY => Yii::t('app', 'Yandex Money'),
            self::WEB_MONEY    => Yii::t('app', 'Web Money'),
            self::TINCOFF      => Yii::t('app', 'Tincoff'),
            self::PRIVAT24     => Yii::t('app', 'Privat24'),
            self::SBERBANK     => Yii::t('app', 'Sberbank'),
            self::QIWI         => Yii::t('app', 'Qiwi'),
        ];
    }

    /**
     * @return array
     */
    public static function currencyLabels(): array
    {
        return [
            self::USD => Yii::t('app', 'USD'),
            self::UAH => Yii::t('app', 'UAH'),
            self::RUB => Yii::t('app', 'RUB'),
            self::EUR => Yii::t('app', 'EUR'),
        ];
    }
    
    /**
     * @return string
     */
    public static function tableName(): string
    {
        return '{{%reserve}}';
    }

    /**
     * @return array
     */
    public function attributeLabels(): array
    {
        return [
            'id'             => '#',
            'payment_system' => Yii::t('app', 'Payment System'),
            'currency'       => Yii::t('app', 'Currency'),
            'sum'            => Yii::t('app', 'Sum'),
            'created_at'     => Yii::t('app', 'Created At'),
            'updated_at'     => Yii::t('app', 'Updated At'),
        ];
    }

    /**
     * @return array
     */
    public function rules(): array
    {
        return [
            ['id', 'integer'],
            [['payment_system', 'currency', 'sum'], 'required'],
            ['sum', 'double'],
            [
                'payment_system',
                'in',
                'range' => [self::PRIVAT24, self::SBERBANK, self::TINCOFF, self::WEB_MONEY, self::YANDEX_MONEY, self::QIWI]
            ],
            ['currency', 'in', 'range' => [self::RUB, self::UAH, self::USD, self::EUR]],
            [['created_at', 'updated_at'], 'safe'],
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
     * Returns payment system label
     * @param $paymentSystem
     * @return string
     */
    public static function getPaymentSystemValue($paymentSystem): string
    {
        $paymentSystems = static::paymentSystemLabels();
        return $paymentSystems[$paymentSystem];
    }

    /**
     * Returns currency label
     * @param $currency
     * @return string
     */
    public static function getCurrencyValue($currency): string
    {
        $currencies = static::currencyLabels();
        return $currencies[$currency];
    }
}
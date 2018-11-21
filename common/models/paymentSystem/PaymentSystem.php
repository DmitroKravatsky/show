<?php

namespace common\models\paymentSystem;

use common\interfaces\IVisible;
use common\traits\VisibleTrait;
use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use common\models\{ bid\BidEntity as Bid, reserve\ReserveEntity as Reserve };

/**
 * This is the model class for table "payment_system".
 *
 * @property int $id
 * @property string $name
 * @property string $currency
 * @property int $visible
 * @property string $payment_system_type
 * @property float $min_transaction_sum
 * @property string $currency_code
 * @property int $created_at
 * @property int $updated_at
 *
 * @property Bid[] $bs
 * @property Bid[] $bs0
 * @property Reserve[] $reserves
 * @property Reserve $reserve
 */
class PaymentSystem extends ActiveRecord implements IVisible
{
    use VisibleTrait;

    const USD = 'usd';
    const UAH = 'uah';
    const RUB = 'rub';
    const EUR = 'eur';
    const WMX = 'wmx';

    const ONLINE_WALLET = 'online_wallet';
    const CREDIT_CARD   = 'credit_card';

    const WEBMONEY_WMX      = 'Webmoney WMX';
    const WEBMONEY_RUB      = 'Webmoney RUB';
    const WEBMONEY_USD      = 'Webmoney USD';
    const WEBMONEY_UAH      = 'Webmoney UAH';
    const WEBMONEY_EUR      = 'Webmoney EUR';
    const VTB_24_RUB        = 'ВТБ 24 RUB';
    const YANDEX_MONEY_RUB  = 'Яндекс.Деньги RUB';
    const SBERBANK_RUB      = 'Сбербанк RUB';
    const PRIVAT_24_UAH     = 'Приват24 UAH';
    const RNK_BANK_RUB      = 'РНК Банк RUB';
    const VISA_MASTER_RUB   = 'Visa/Master руб RUB';

    /**
     * @return string
     */
    public static function tableName(): string
    {
        return 'payment_system';
    }

    /**
     * @return array
     */
    public static function nameLabels(): array
    {
        return [
            self::WEBMONEY_WMX     => self::WEBMONEY_WMX,
            self::WEBMONEY_RUB     => self::WEBMONEY_RUB,
            self::WEBMONEY_USD     => self::WEBMONEY_USD,
            self::WEBMONEY_UAH     => self::WEBMONEY_UAH,
            self::VTB_24_RUB       => self::VTB_24_RUB,
            self::YANDEX_MONEY_RUB => self::YANDEX_MONEY_RUB,
            self::SBERBANK_RUB     => self::SBERBANK_RUB,
            self::PRIVAT_24_UAH    => self::PRIVAT_24_UAH,
            self::RNK_BANK_RUB     => self::RNK_BANK_RUB,
            self::VISA_MASTER_RUB  => self::VISA_MASTER_RUB,
        ];
    }

    /**
     * @param $filterCurrency string
     * @return array
     */
    public static function filteredNameLabels($filterCurrency = null): array
    {
        if ($filterCurrency == null) {
            return static::nameLabels();
        }

        $nameLabels = PaymentSystem::findPaymentSystemNamesByCurrencyType($filterCurrency);
        $filteredNames = [];
        foreach ($nameLabels as $nameLabel) {
            $filteredNames[$nameLabel->name] = $nameLabel->name;
        }

        return $filteredNames;
    }

    /**
     * Returns payment system name label
     * @param $name
     * @return string
     */
    public static function getPaymentNameValue($name): string
    {
        $paymentSystems = static::nameLabels();
        return $paymentSystems[$name];
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
            self::WMX => Yii::t('app', 'WMX'),
        ];
    }

    /**
     * @return array
     */
    public static function paymentSystemTypeLabels(): array
    {
        return [
            self::ONLINE_WALLET => Yii::t('app', 'Online wallet'),
            self::CREDIT_CARD => Yii::t('app', 'Credit card'),
        ];
    }

    /**
     * Returns payment system type label
     * @param $type
     * @return string
     */
    public static function getPaymentSystemTypeValue($type): string
    {
        $paymentSystems = static::paymentSystemTypeLabels();
        return $paymentSystems[$type];
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

    /**
     * @return array
     */
    public function rules(): array
    {
        return [
            [['name', 'min_transaction_sum', 'currency_code',], 'required'],
            [['min_transaction_sum'], 'double', 'min' => 10],
            [['currency'], 'string'],
            [['visible', 'created_at', 'updated_at'], 'integer'],
            [['name', 'currency_code',], 'string', 'max' => 50],
            [['payment_system_type'],  'in', 'range' => [self::ONLINE_WALLET, self::CREDIT_CARD]],
            [['currency'], 'in', 'range' => [self::RUB, self::UAH, self::USD, self::EUR, self::WMX]],
        ];
    }

    /**
     * @return array
     */
    public function attributeLabels(): array
    {
        return [
            'id'                  => 'ID',
            'name'                => Yii::t('app', 'Name'),
            'currency'            => Yii::t('app', 'Currency'),
            'visible'             => Yii::t('app', 'Visible'),
            'payment_system_type' => Yii::t('app', 'Payment system type'),
            'min_transaction_sum' => Yii::t('app', 'Minimum Transaction Sum'),
            'Currency Code'       => Yii::t('app', 'Currency Code'),
            'created_at'          => Yii::t('app', 'Created At'),
            'updated_at'          => Yii::t('app', 'Updated At'),
        ];
    }

    /**
     * @return array
     */
    public function behaviors(): array
    {
        return [
            TimestampBehavior::class,
        ];
    }

    public function toggleVisible(): bool
    {
        $this->visible = !$this->visible;
        return $this->save(false, ['visible']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBs()
    {
        return $this->hasMany(Bid::class, ['from_payment_system_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBs0()
    {
        return $this->hasMany(Bid::class, ['to_payment_system_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getReserves()
    {
        return $this->hasMany(Reserve::class, ['payment_system_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getReserve()
    {
        return $this->hasOne(Reserve::class, ['payment_system_id' => 'id']);
    }

    public static function getList($params ,$onlyVisible = true)
    {
        $query = static::find()->select([
            'id', 'name', 'currency', 'payment_system_type', 'min_transaction_sum'
        ]);

        if (isset($params['filter'])) {
            $query->andWhere(['currency' => $params['filter']]);
        }

        if ($onlyVisible) {
            $query->where(['visible' => self::VISIBLE_YES]);
        }

        return $query->orderBy(['created_at' => SORT_DESC])->all();
    }

    public static function findPaymentSystemNamesByCurrencyType($currencyType) {
        return PaymentSystem::find()->select('name')->where(['currency' => $currencyType])->all();
    }
}

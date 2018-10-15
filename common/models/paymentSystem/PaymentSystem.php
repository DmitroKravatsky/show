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
 * @property int $created_at
 * @property int $updated_at
 *
 * @property Bid[] $bs
 * @property Bid[] $bs0
 * @property Reserve[] $reserves
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
            [['name', 'min_transaction_sum',], 'required'],
            [['min_transaction_sum'], 'double', 'min' => 10],
            [['currency'], 'string'],
            [['visible', 'created_at', 'updated_at'], 'integer'],
            [['name'], 'string', 'max' => 50],
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

    public static function getList($params ,$onlyVisible = true)
    {
        $query = static::find()->select([
            'id', 'name', 'currency', 'payment_system_type'
        ]);

        if (isset($params['filter'])) {
            $query->andWhere(['currency' => $params['filter']]);
        }

        if ($onlyVisible) {
            $query->where(['visible' => self::VISIBLE_YES]);
        }

        return $query->orderBy(['created_at' => SORT_DESC])->all();
    }
}

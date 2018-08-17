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
}
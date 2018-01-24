<?php

namespace common\models\reserve;

use yii\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;

/**
 * Class ReserveEntity
 * @package common\models\reserve
 * @property integer $id
 * @property string $payment_system
 * @property string $currency
 * @property float $sum
 * @property string $image
 * @property integer $created_at
 * @property  integer $updated_at
 */
class ReserveEntity extends ActiveRecord
{
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
            'payment_system' => 'Платежная система',
            'currency'       => 'Валюта',
            'image'          => 'Изображение',
            'sum'            => 'Сумма',
            'created_at'     => 'Дата создания',
            'updated_at'     => 'Дата изменения',
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
            ['image', 'string'],
            [['created_at', 'updated_at'], 'safe'],
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
<?php

namespace common\models\exchangeRates;

use Yii;
use common\models\paymentSystem\PaymentSystem;
use yii\db\ActiveRecord;
use yii\web\NotFoundHttpException;

/**
 * This is the model class for table "exchange_rates".
 *
 * @property int $id
 * @property int $from_payment_system_id
 * @property int $to_payment_system_id
 * @property double $value
 * @property int $created_at
 * @property int $updated_at
 *
 * @property PaymentSystem $fromPaymentSystem
 * @property PaymentSystem $toPaymentSystem
 */
class ExchangeRates extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return 'exchange_rates';
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['from_payment_system_id', 'to_payment_system_id'], 'required'],
            [['from_payment_system_id', 'to_payment_system_id', 'created_at', 'updated_at'], 'integer'],
            [['value'], 'number'],
            [['from_payment_system_id'], 'exist', 'skipOnError' => true, 'targetClass' => PaymentSystem::class, 'targetAttribute' => ['from_payment_system_id' => 'id']],
            [['to_payment_system_id'], 'exist', 'skipOnError' => true, 'targetClass' => PaymentSystem::class, 'targetAttribute' => ['to_payment_system_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels(): array
    {
        return [
            'id' => 'ID',
            'from_payment_system_id' => Yii::t('app', 'From Payment System'),
            'to_payment_system_id' => Yii::t('app', 'To Payment System'),
            'value' => Yii::t('app', 'Value'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
        ];
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
     * @param $fromPaymentSystem
     * @param $toPaymentSystem
     * @param $fromAmount
     * @return float
     * @throws NotFoundHttpException
     */
    public function calculateAmountByRate($fromPaymentSystem, $toPaymentSystem, $fromAmount)
    {
        $exchangeRates = static::findOne(['from_payment_system_id' => $fromPaymentSystem, 'to_payment_system_id' => $toPaymentSystem]);
        if (!$exchangeRates) {
            throw new NotFoundHttpException();
        }

        return round($fromAmount * $exchangeRates->value, 2);
    }
}

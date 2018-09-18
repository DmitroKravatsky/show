<?php

namespace common\models\reserve;

use common\interfaces\IVisible;
use common\models\reserve\repositories\RestReserveRepository;
use common\traits\VisibleTrait;
use rest\behaviors\ValidationExceptionFirstMessage;
use yii\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;
use Yii;
use common\models\paymentSystem\PaymentSystem;

/**
 * Class ReserveEntity
 * @package common\models\reserve
 *
 * @mixin ValidationExceptionFirstMessage
 *
 * @property integer $id
 * @property float $sum
 * @property boolean $visible
 * @property integer $created_at
 * @property integer $updated_at
 *
 * @property PaymentSystem $paymentSystem
 */
class ReserveEntity extends ActiveRecord implements IVisible
{
    use RestReserveRepository, VisibleTrait;
    
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
            'id'                => '#',
            'payment_system_id' => Yii::t('app', 'Payment System'),
            'currency'          => Yii::t('app', 'Currency'),
            'sum'               => Yii::t('app', 'Sum'),
            'visible'           => Yii::t('app', 'Visible'),
            'created_at'        => Yii::t('app', 'Created At'),
            'updated_at'        => Yii::t('app', 'Updated At'),
        ];
    }

    /**
     * @return array
     */
    public function rules(): array
    {
        return [
            ['id', 'integer'],
            [['payment_system_id', 'currency', 'sum'], 'required'],
            ['sum', 'double'],
            [['payment_system_id'], 'exist', 'skipOnError' => true, 'targetClass' => PaymentSystem::class, 'targetAttribute' => ['payment_system_id' => 'id']],
            [['created_at', 'updated_at'], 'safe'],
            [['visible'], 'boolean'],
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

    public function toggleVisible(): bool
    {
        $this->visible = !$this->visible;
        return $this->save(false, ['visible']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPaymentSystem()
    {
        return $this->hasOne(PaymentSystem::class, ['id' => 'payment_system_id']);
    }
}

<?php

namespace common\models\wallet;

use common\models\wallet\repositories\RestWalletRepository;
use rest\behaviors\ValidationExceptionFirstMessage;
use rest\modules\api\v1\authorization\models\RestUserEntity;
use yii\behaviors\TimestampBehavior;
use Yii;
use common\models\paymentSystem\PaymentSystem;
use common\models\user\User;

/**
 * Class WalletEntity
 * @package common\models\wallet
 *
 * @mixin ValidationExceptionFirstMessage
 *
 * @property integer $id
 * @property integer $created_by
 * @property string $name
 * @property string $number
 * @property string $payment_system_id
 * @property integer $created_at
 * @property integer $updated_at
 *
 * @property PaymentSystem $paymentSystem
 * @property User $createdBy
 */
class WalletEntity extends \yii\db\ActiveRecord
{
    use RestWalletRepository;

    /**
     * @return string
     */
    public static function tableName(): string
    {
        return '{{%wallet}}';
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
     * @return array
     */
    public function attributeLabels(): array
    {
        return [
            'id'                => '#',
            'name'              => Yii::t('app', 'Name'),
            'payment_system_id' => Yii::t('app', 'Payment System'),
            'created_by'        => Yii::t('app', 'Created By'),
            'number'            => Yii::t('app', 'Wallet Number'),
            'created_at'        => Yii::t('app', 'Created At'),
            'updated_at'        => Yii::t('app', 'Updated At')
        ];
    }

    /**
     * @return array
     */
    public function rules(): array
    {
        return [
            [['name', 'number', 'payment_system_id'], 'required'],
            ['created_by', 'default', 'value' => \Yii::$app->user->id],
            [
                'created_by',
                'exist',
                'skipOnError'     => false,
                'targetClass'     => RestUserEntity::class,
                'targetAttribute' => ['created_by' => 'id'],
            ],
            ['name', 'string', 'max' => 64],
            ['number', 'string', 'max' => 32],
            [
                ['payment_system_id'],
                'exist',
                'skipOnError' => true,
                'targetClass' => PaymentSystem::class,
                'targetAttribute' => ['payment_system_id' => 'id']
            ],
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPaymentSystem()
    {
        return $this->hasOne(PaymentSystem::class, ['id' => 'payment_system_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCreatedBy()
    {
        return $this->hasOne(User::class, ['id' => 'created_by']);
    }
}

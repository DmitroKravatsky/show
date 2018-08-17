<?php

namespace common\models\wallet;

use common\models\wallet\repositories\RestWalletRepository;
use rest\behaviors\ValidationExceptionFirstMessage;
use rest\modules\api\v1\authorization\models\RestUserEntity;
use yii\behaviors\TimestampBehavior;
use Yii;

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
 * @property string $payment_system
 * @property integer $created_at
 * @property integer $updated_at
 */
class WalletEntity extends \yii\db\ActiveRecord
{
    use RestWalletRepository;

    const YANDEX_MONEY = 'yandex_money';
    const WEB_MONEY    = 'web_money';
    const TINCOFF      = 'tincoff';
    const PRIVAT24     = 'privat24';
    const SBERBANK     = 'sberbank';
    const QIWI         = 'qiwi';

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
            'id'             => '#',
            'name'           => Yii::t('app', 'Name'),
            'payment_system' => Yii::t('app', 'Payment System'),
            'created_by'     => Yii::t('app', 'Created By'),
            'number'         => Yii::t('app', 'Wallet Number'),
            'created_at'     => Yii::t('app', 'Created At'),
            'updated_at'     => Yii::t('app', 'Updated At')
        ];
    }

    /**
     * @return array
     */
    public function rules(): array
    {
        return [
            [['name', 'number', 'payment_system'], 'required'],
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
                'payment_system',
                'in',
                'range' => [
                    self::PRIVAT24, self::QIWI, self::YANDEX_MONEY, self::WEB_MONEY, self::SBERBANK, self::TINCOFF
                ]
            ]
        ];
    }
}
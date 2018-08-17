<?php

namespace common\models\bidHistory;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;
use common\models\bid\BidEntity;

/**
 * This is the model class for table "bid_history".
 *
 * @property int $id
 * @property int $bid_id
 * @property int $status
 * @property int $time
 *
 * @property BidEntity $bid
 */
class BidHistory extends ActiveRecord
{
    const STATUS_ACCEPTED    = 'accepted';
    const STATUS_IN_PROGRESS = 'in_progress';
    const STATUS_DONE        = 'done';
    const STATUS_REJECTED    = 'rejected';
    const STATUS_PAID        = 'paid';

    /**
     * @return array
     */
    public static function statusLabels(): array
    {
        return [
            self::STATUS_ACCEPTED    => Yii::t('app', 'Accepted'),
            self::STATUS_IN_PROGRESS => Yii::t('app', 'In progress'),
            self::STATUS_DONE        => Yii::t('app', 'Done'),
            self::STATUS_REJECTED    => Yii::t('app', 'Rejected'),
            self::STATUS_PAID        => Yii::t('app', 'Paid'),
        ];
    }

    /**
     * @return string
     */
    public static function tableName(): string
    {
        return '{{%bid_history}}';
    }

    /**
     * @return array
     */
    public function rules(): array
    {
        return [
            [['bid_id', 'status'], 'required'],
            [['bid_id', 'time'], 'integer'],
            [['bid_id'], 'exist', 'skipOnError' => true, 'targetClass' => BidEntity::class, 'targetAttribute' => ['bid_id' => 'id']],
        ];
    }

    /**
     * @return array
     */
    public function behaviors(): array
    {
        return [
            [
                'class' => TimestampBehavior::class,
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => 'time',
                ],
            ],
        ];
    }

    /**
     * @return array
     */
    public function attributeLabels(): array
    {
        return [
            'id' => 'ID',
            'bid_id' => Yii::t('app', 'Bid'),
            'status' => Yii::t('app', 'Status'),
            'time' => Yii::t('app', 'Time'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBid(): ActiveQuery
    {
        return $this->hasOne(BidEntity::class, ['id' => 'bid_id']);
    }

    /**
     * @param $status
     * @return string
     */
    public static function getStatusValue($status): string
    {
        $statuses = static::statusLabels();
        return $statuses[$status];
    }
}

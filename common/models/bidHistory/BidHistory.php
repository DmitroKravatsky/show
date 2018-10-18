<?php

namespace common\models\bidHistory;

use common\models\user\User;
use common\models\userProfile\UserProfileEntity;
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
 * @property int $processed_by
 * @property int $in_progress_by_manager
 * @property int $status
 * @property int $time
 *
 * @property BidEntity $bid
 * @property User $processedBy
 * @property User $inProgressByManager
 * @property UserProfileEntity $processedByProfile
 */
class BidHistory extends ActiveRecord
{
    const STATUS_NEW             = 'new';
    const STATUS_PAID_BY_CLIENT  = 'paid_by_client';
    const STATUS_IN_PROGRESS     = 'in_progress';
    const STATUS_PAID_BY_US_DONE = 'paid_by_us_done';
    const STATUS_REJECTED        = 'rejected';

    /**
     * @return array
     */
    public static function statusLabels(): array
    {
        return [
            self::STATUS_NEW             => Yii::t('app', 'New'),
            self::STATUS_PAID_BY_CLIENT  => Yii::t('app', 'Paid by client'),
            self::STATUS_IN_PROGRESS     => Yii::t('app', 'In progress'),
            self::STATUS_PAID_BY_US_DONE => Yii::t('app', 'Paid by us'),
            self::STATUS_REJECTED        => Yii::t('app', 'Rejected'),
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
            [['bid_id', 'time', 'processed_by', 'in_progress_by_manager',], 'integer'],
            [['bid_id'], 'exist', 'skipOnError' => true, 'targetClass' => BidEntity::class, 'targetAttribute' => ['bid_id' => 'id']],
            [['processed_by'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['processed_by' => 'id']],
            [
                ['in_progress_by_manager'],
                'exist',
                'skipOnError' => true,
                'targetClass' => User::class,
                'targetAttribute' => ['in_progress_by_manager' => 'id']
            ],
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
            'id'                     => 'ID',
            'bid_id'                 => Yii::t('app', 'Bid'),
            'processed_by'           => Yii::t('app', 'Processed By'),
            'in_progress_by_manager' => Yii::t('app', 'In Progress By Manager'),
            'status'                 => Yii::t('app', 'Status'),
            'time'                   => Yii::t('app', 'Time'),
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
     * @return \yii\db\ActiveQuery
     */
    public function getProcessedBy(): ActiveQuery
    {
        return $this->hasOne(User::class, ['id' => 'processed_by']);
    }

    /**
     * Returns bid's author
     * @return \yii\db\ActiveQuery
     */
    public function getInProgressByManager()
    {
        return $this->hasOne(User::class, ['id' => 'in_progress_by_manager']);
    }

    /**
     * Returns bid's author
     * @return \yii\db\ActiveQuery
     */
    public function getProcessedByProfile()
    {
        return $this->hasOne(UserProfileEntity::class, ['user_id' => 'processed_by']);
    }

    /**
     * @param $status
     * @return string
     */
    public static function getStatusValue($status):string
    {
        $statuses = static::statusLabels();
        return $statuses[$status];
    }

    /**
     * Returns a message by a BidHistory model
     * @param BidHistory $bidHistory
     * @return mixed
     */
    public static function getMessageByBid(BidHistory $bidHistory)
    {
        $messages = [
            self::STATUS_NEW => Yii::t('app', 'A new application has been created by') . ' ' . $bidHistory->bid->author->fullName,
            self::STATUS_IN_PROGRESS => Yii::t('app', 'The bid was processed by') . ' ' . $bidHistory->inProgressByManager->fullName,
            self::STATUS_PAID_BY_CLIENT => Yii::t('app', 'The bid has been paid by client') . ' ' . $bidHistory->bid->author->fullName,
            self::STATUS_PAID_BY_US_DONE => Yii::t('app', 'The bid has been paid by us') . ' (' . $bidHistory->processedBy->fullName,
            self::STATUS_REJECTED => Yii::t('app', 'The bid was rejected by') . ' (' . $bidHistory->processedBy->fullName  . ')',
        ];

        return $messages[$bidHistory->status];
    }
}

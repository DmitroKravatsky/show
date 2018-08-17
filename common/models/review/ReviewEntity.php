<?php

namespace common\models\review;

use common\models\review\repositories\RestReviewRepository;
use rest\behaviors\ValidationExceptionFirstMessage;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use common\models\user\User;
use Yii;

/**
 * Class ReviewEntity
 * @package common\models\review
 *
 * @mixin ValidationExceptionFirstMessage
 * 
 * @property integer $id
 * @property integer $created_by
 * @property string $text
 * @property integer $created_at
 * @property integer $updated_at
 *
 * @property User $createdBy
 */
class ReviewEntity extends ActiveRecord
{
    use RestReviewRepository;

    /**
     * @return string
     */
    public static function tableName(): string 
    {
        return '{{%review}}';
    }

    /**
     * @return array
     */
    public function attributeLabels(): array
    {
        return [
            'id'         => '#',
            'created_by' => Yii::t('app', 'Created By'),
            'text'       => Yii::t('app', 'Text'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
        ];
    }

    /**
     * @return array
     */
    public function rules(): array 
    {
        return [
            ['text', 'string'],
            ['created_by', 'default', 'value' => \Yii::$app->user->id],
            ['text', 'required'],
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

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCreatedBy()
    {
        return $this->hasOne(User::class, ['id' => 'created_by']);
    }
}

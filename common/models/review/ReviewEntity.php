<?php

namespace common\models\review;

use common\models\review\repositories\RestReviewRepository;
use rest\behaviors\ResponseBehavior;
use rest\behaviors\ValidationExceptionFirstMessage;
use yii\behaviors\TimestampBehavior;

/**
 * Class ReviewEntity
 * @package common\models\review
 * @mixin ValidationExceptionFirstMessage
 * @mixin ResponseBehavior
 * 
 * @property integer $id
 * @property integer $created_by
 * @property string $text
 * @property integer $created_at
 * @property integer $updated_at
 */
class ReviewEntity extends \yii\db\ActiveRecord
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
            'created_by' => 'Автор',
            'text'       => 'Текст отзыва',
            'created_at' => 'Дата создания',
            'updated_at' => 'Дата обновления',
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
            'TimestampBehavior'               => TimestampBehavior::className(),
            'ResponseBehavior'                => ResponseBehavior::className(),
            'ValidationExceptionFirstMessage' => ValidationExceptionFirstMessage::className(),
        ];
    }
}
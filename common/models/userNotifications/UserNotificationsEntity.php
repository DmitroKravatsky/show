<?php

namespace common\models\userNotifications;

use common\models\userNotifications\repositories\RestUserNotificationsRepository;
use rest\behaviors\ValidationExceptionFirstMessage;
use rest\modules\api\v1\authorization\models\RestUserEntity;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

/**
 * Class UserNotificationsEntity
 * @package common\models\userNotifications
 *
 * @mixin ValidationExceptionFirstMessage
 *
 * @property integer $id
 * @property integer $recipient_id
 * @property string $text
 * @property string $status
 * @property integer $created_at
 * @property integer $updated_at
 */
class UserNotificationsEntity extends ActiveRecord
{
    use RestUserNotificationsRepository;

    const STATUS_READ   = 'read';
    const STATUS_UNREAD = 'unread';

    /**
     * @return string
     */
    public static function tableName(): string
    {
        return '{{%user_notifications}}';
    }

    /**
     * @return array
     */
    public function attributeLabels(): array
    {
        return [
            'id'           => '#',
            'recipient_id' => 'Получатель',
            'text'         => 'Текст',
            'status'       => 'Статус',
            'created_at'   => 'Дата создания',
            'updated_at'   => 'Дата обновления',
        ];
    }

    /**
     * @return array
     */
    public function rules(): array
    {
        return [
            ['recipient_id', 'integer'],
            ['text', 'string'],
            ['status', 'in', 'range' => [self::STATUS_READ, self::STATUS_UNREAD]],
            [
                'recipient_id',
                'exist',
                'skipOnError'     => false,
                'targetClass'     => RestUserEntity::class,
                'targetAttribute' => ['recipient_id' => 'id'],
            ],
        ];
    }

    /**
     * @return array
     */
    public function behaviors(): array
    {
        return [
            'TimestampBehavior'               => TimestampBehavior::class,
            'ValidationExceptionFirstMessage' => ValidationExceptionFirstMessage::class,
        ];
    }
}
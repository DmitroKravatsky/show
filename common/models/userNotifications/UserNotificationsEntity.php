<?php

namespace common\models\userNotifications;

use common\models\userNotifications\repositories\RestUserNotificationsRepository;
use common\models\userProfile\UserProfileEntity;
use rest\behaviors\ValidationExceptionFirstMessage;
use rest\modules\api\v1\authorization\models\RestUserEntity;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\web\NotFoundHttpException;

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

    /**
     * Generates a message for a new user notification
     *
     * @param $params array
     *
     * @return string
     *
     * @throws NotFoundHttpException
     */
    public static function getMessageForNewUser(array $params)
    {
        $phone_number = $params['phone_number'];
        $email = $params['email'] ?? 'not set';

        $message = <<<EOT
Новый пользователь был зарегистрирован. Регистрация была провердена с номером телефона {$phone_number} 
EOT;
        return $message;
    }

    /**
     * Generates a message for a newly created bid. Status accepted is default
     *
     * @param $params array
     *
     * @return string
     *
     * @throws NotFoundHttpException
     */
    public static function getMessageForNewBid(array $params)
    {
//        $fullName = UserProfileEntity::getFullName($params['created_by']);
        $sum = $params['to_sum'];
        $currency = $params['to_currency'];
        $to_wallet = $params['to_wallet'];

        $message = <<<EOT
Ваша заявка приянта. Перевод на карту {$sum} {$currency} через приложение Wallet. Получатель:
Карта/счет {$to_wallet}
EOT;

        return $message;
    }
}

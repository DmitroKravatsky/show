<?php

namespace common\models\oauth;

use common\models\oauth\repositories\RestOauthRepository;
use yii\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;
use common\models\User;

/**
 * Class OauthEntity
 * @package common\models\oauth
 *
 * @property integer $id
 * @property integer $user_id
 * @property string $source
 * @property string $source_id
 * @property integer $created_at
 * @property integer $updated_at
 */
class OauthEntity extends ActiveRecord
{
    use RestOauthRepository;

    const FB    = 'fb';
    const VK    = 'vk';
    const GMAIL = 'gmail';

    /**
     * @return string
     */
    public static function tableName(): string
    {
        return '{{%oauth}}';
    }

    /**
     * @return array
     */
    public function attributeLabels(): array
    {
        return [
            'id'         => '#',
            'user_id'    => 'Пользователь',
            'source'     => 'Социальная сеть',
            'source_id'  => 'Пользователь в социальной сети',
            'created_at' => 'Дата создания',
            'updated_at' => 'Дата изменения',
        ];
    }

    /**
     * @return array
     */
    public function rules(): array
    {
        return [
            [['id', 'user_id'], 'integer'],
            [
                'user_id',
                'exist',
                'skipOnError'     => false,
                'targetClass'     => User::className(),
                'targetAttribute' => ['user_id' => 'id'],
            ],
            [['user_id', 'source', 'source_id'], 'required'],
            ['source', 'in', 'range' => [self::FB, self::VK, self::GMAIL]],
            [['created_at', 'updated_at'], 'safe'],
        ];
    }

    /**
     * @return array
     */
    public function behaviors(): array
    {
        return [
            TimestampBehavior::className(),
        ];
    }
}
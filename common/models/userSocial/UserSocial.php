<?php

namespace common\models\userSocial;

use Yii;
use common\models\user\User;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "user_social".
 *
 * @property int $id
 * @property int $user_id
 * @property string $source_id
 * @property string $source_name
 * @property int $created_at
 * @property int $updated_at
 *
 * @property User $user
 */
class UserSocial extends ActiveRecord
{
    const SOURCE_FB = 'fb';
    const SOURCE_GMAIL = 'gmail';

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'user_social';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id', 'source_id'], 'required'],
            [['user_id', 'created_at', 'updated_at'], 'integer'],
            [['source_name'], 'string'],
            [['source_id'], 'string', 'max' => 255],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['user_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => 'User ID',
            'source_id' => 'Source ID',
            'source_name' => 'Source Name',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    public function behaviors()
    {
        return [
            TimestampBehavior::class,
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::class, ['id' => 'user_id']);
    }
}

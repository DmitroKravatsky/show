<?php

namespace common\models\userProfile;

use yii\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;

/**
 * Class UserProfileEntity
 * @package common\models\userProfile
 * 
 * @property integer $id
 * @property integer $user_id
 * @property string $name
 * @property string $last_name
 * @property string $phone_number
 * @property string $email
 * @property string $avatar
 * @property integer $created_at
 * @property integer $updated_at
 */
class UserProfileEntity extends ActiveRecord
{
    const SCENARIO_CREATE = 'create';
    const SCENARIO_UPDATE = 'update';

    /**
     * @return string
     */
    public static function tableName(): string
    {
        return '{{%user_profile}}';
    }

    /**
     * @return array
     */
    public function attributeLabels(): array
    {
        return [
            'id'           => '#',
            'user_id'      => 'Пользователь',
            'name'         => 'Имя',
            'last_name'    => 'Фамилия',
            'phone_number' => 'Номер телефона',
            'email'        => 'Email',
            'avatar'       => 'Аватар',
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
            [['id', 'user_id'], 'integer'],
            ['user_id', 'default', 'value' => \Yii::$app->user->id],
            [['name', 'last_name', 'phone_number', 'email'], 'string', 'max' => 20],
            [['name', 'last_name', 'phone_number', 'email'], 'required'],
            ['email', 'email'],
            ['avatar', 'string'],
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

    /**
     * @return array
     */
    public function scenarios(): array
    {
        $scenarios = parent::scenarios();

        $scenarios[self::SCENARIO_CREATE] = ['id', 'user_id', 'name', 'last_name', 'phone_number', 'email', 'avatar',];
        $scenarios[self::SCENARIO_UPDATE] = ['id', 'user_id', 'name', 'last_name', 'phone_number', 'email', 'avatar',];

        return $scenarios;
    }
}
<?php

namespace common\models\userProfile;

use Yii;
use yii\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;
use common\behaviors\ImageBehavior;
use common\models\userSocial\UserSocial;
use common\models\userProfile\repositories\RestUserProfileRepository;
use common\validators\Base64Validator;
use rest\behaviors\ValidationExceptionFirstMessage;
use rest\modules\api\v1\authorization\models\RestUserEntity;

/**
 * Class UserProfileEntity
 * @package common\models\userProfile
 *
 * @mixin ValidationExceptionFirstMessage
 *
 * @property integer $id
 * @property integer $user_id
 * @property string $name
 * @property string $last_name
 * @property string $avatar
 * @property string $avatar_base64
 * @property integer $created_at
 * @property integer $updated_at
 * @property UserSocial[] $userSocials
 */
class UserProfileEntity extends ActiveRecord
{
    use RestUserProfileRepository;
    
    const SCENARIO_CREATE = 'create';
    const SCENARIO_UPDATE = 'update';

    public $avatar_base64;

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
            'user_id'      => Yii::t('app', 'User'),
            'name'         => Yii::t('app', 'First Name'),
            'last_name'    => Yii::t('app', 'Last Name'),
            'avatar'       => Yii::t('app', 'Avatar'),
            'created_at'   => Yii::t('app', 'Created At'),
            'updated_at'   => Yii::t('app', 'Updated At'),
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
            [['name', 'last_name',], 'string', 'max' => 20],
            [['avatar', 'avatar_base64',], 'string'],
            [['created_at', 'updated_at'], 'safe'],
            [['avatar'], 'file', 'extensions' => 'png, jpg, jpeg'],
            [['avatar_base64'], Base64Validator::class, 'extensions' => ['jpg', 'jpeg', 'png']],
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
            [
                'class' => ImageBehavior::class,
                'savePath' => 'images/profile',
                'attributeName' => 'avatar',
            ],
        ];
    }

    /**
     * @return array
     */
    public function scenarios(): array
    {
        $scenarios = parent::scenarios();

        $scenarios[self::SCENARIO_CREATE] = ['id', 'user_id', 'name', 'last_name', 'avatar',];
        $scenarios[self::SCENARIO_UPDATE] = ['id', 'user_id', 'name', 'last_name', 'avatar', 'avatar_base64'];

        return $scenarios;
    }

    /** Get user data from user table
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(RestUserEntity::class, ['id' => 'user_id']);
    }

    /**
     * Returns users name and last_name
     * @return string
     */
    public function getUserFullName()
    {
        return $this->name . ' ' . $this->last_name;
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUserSocials()
    {
        return $this->hasMany(UserSocial::className(), ['user_id' => 'user_id']);
    }
}

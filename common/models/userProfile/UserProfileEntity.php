<?php

namespace common\models\userProfile;

use common\models\userProfile\repositories\RestUserProfileRepository;
use rest\behaviors\ValidationExceptionFirstMessage;
use rest\modules\api\v1\authorization\models\RestUserEntity;
use yii\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;
use yii\helpers\FileHelper;
use yii\web\UploadedFile;
use Yii;

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
 * @property integer $created_at
 * @property integer $updated_at
 */
class UserProfileEntity extends ActiveRecord
{
    use RestUserProfileRepository;
    
    const SCENARIO_CREATE = 'create';
    const SCENARIO_UPDATE = 'update';

    /**
     * @var UploadedFile
     */
    public $image;

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
            [['name', 'last_name',], 'string', 'max' => 20],
            [['name', 'last_name',], 'required'],
            ['avatar', 'string'],
            [['created_at', 'updated_at'], 'safe'],
            [['image'], 'file', 'extensions' => 'png, jpg, jpeg'],
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
     * @return array
     */
    public function scenarios(): array
    {
        $scenarios = parent::scenarios();

        $scenarios[self::SCENARIO_CREATE] = ['id', 'user_id', 'name', 'last_name', 'avatar',];
        $scenarios[self::SCENARIO_UPDATE] = ['id', 'user_id', 'name', 'last_name', 'avatar',];

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
     * @throws \yii\base\ErrorException
     * @throws \yii\base\Exception
     * @return bool
     */
    public function upload(): bool
    {
        $dirPath = $this->getDirPath();
        $imageName = time() . '.' . $this->image->extension;
        $imagePath = $dirPath . $imageName;

        FileHelper::removeDirectory(dirname($imagePath));
        FileHelper::createDirectory(dirname($imagePath));

        if ($this->validate()) {
            $this->image->saveAs($imagePath);
            $this->avatar = $imageName;

            return $this->save(false);
        }

        return false;
    }

    /**
     * @return string
     */
    protected function getDirPath(): string
    {
        return Yii::getAlias('@image') . DIRECTORY_SEPARATOR . 'profile' . DIRECTORY_SEPARATOR . $this->id . DIRECTORY_SEPARATOR;
    }

    /**
     * @return string
     */
    public function getImagePath(): string
    {
        return Yii::getAlias('/frontend/web/image') . DIRECTORY_SEPARATOR . 'profile' . DIRECTORY_SEPARATOR. $this->id . DIRECTORY_SEPARATOR . $this->avatar;
    }
}
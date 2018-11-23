<?php

namespace common\models\review;

use Yii;
use common\traits\VisibleTrait;
use common\behaviors\ImageBehavior;
use common\models\review\repositories\RestReviewRepository;
use common\models\userProfile\UserProfileEntity;
use common\models\user\User;
use common\interfaces\IVisible;
use rest\behaviors\ValidationExceptionFirstMessage;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

/**
 * Class ReviewEntity
 * @package common\models\review
 *
 * @mixin ValidationExceptionFirstMessage
 * 
 * @property integer $id
 * @property integer $created_by
 * @property string  $text
 * @property string  $avatar
 * @property integer $terms_condition
 * @property integer $visible
 * @property integer $created_at
 * @property integer $updated_at
 *
 * @property User $createdBy
 * @property UserProfileEntity $createdByProfile
 */
class ReviewEntity extends ActiveRecord implements IVisible
{
    use RestReviewRepository, VisibleTrait;

    const SCENARIO_CREATE = 'create';

    const PATH_TO_FAKE_USER_PHOTO = 'images/fakeusers';

    public $terms_condition;

    /**
     * @return string
     */
    public static function tableName(): string 
    {
        return '{{%review}}';
    }

    public function scenarios(): array
    {
        $scenarios = parent::scenarios();

        $scenarios[self::SCENARIO_CREATE] = ['terms_condition', 'created_by', 'text',];

        return $scenarios;
    }

    /**
     * @return array
     */
    public function attributeLabels(): array
    {
        return [
            'id'              => '#',
            'created_by'      => Yii::t('app', 'Created By'),
            'text'            => Yii::t('app', 'Text'),
            'terms_condition' => Yii::t('app', 'Terms Condition'),
            'created_at'      => Yii::t('app', 'Created At'),
            'updated_at'      => Yii::t('app', 'Updated At'),
            'avatar'          => Yii::t('app', 'Avatar'),
            'name'            => Yii::t('app', 'First Name'),
            'last_name'       => Yii::t('app', 'Last Name'),
            'visible'         => Yii::t('app', 'Visible')
        ];
    }

    /**
     * @return array
     */
    public function rules(): array 
    {
        return [
            [['text', 'avatar', 'name', 'last_name'], 'string'],
            ['created_by', 'default', 'value' => Yii::$app->user->id],
            ['text', 'required'],
            ['terms_condition', 'required', 'requiredValue' => 1, 'on' => self::SCENARIO_CREATE],
            [['avatar'], 'file', 'extensions' => 'png, jpg, jpeg'],
            [['visible'], 'integer'],
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
                'savePath' => self::PATH_TO_FAKE_USER_PHOTO,
                'attributeName' => 'avatar',
            ]
        ];
    }

    public function toggleVisible(): bool
    {
        $this->visible = !$this->visible;
        return $this->save(false, ['visible']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCreatedBy()
    {
        return $this->hasOne(User::class, ['id' => 'created_by']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCreatedByProfile()
    {
        return $this->hasOne(UserProfileEntity::class, ['user_id' => 'created_by']);
    }
}

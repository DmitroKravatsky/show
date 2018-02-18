<?php

namespace rest\modules\api\v1\authorization\models;

use rest\behaviors\ResponseBehavior;
use rest\behaviors\ValidationExceptionFirstMessage;
use rest\modules\api\v1\authorization\models\repositories\AuthorizationJwt;
use rest\modules\api\v1\authorization\models\repositories\AuthorizationRepository;
use yii\behaviors\TimestampBehavior;
use common\models\user\User;
use rest\modules\api\v1\authorization\models\repositories\SocialRepository;
use Yii;

/**
 * Class RestUserEntity
 * @mixin ValidationExceptionFirstMessage
 * @mixin ResponseBehavior
 * @package rest\modules\api\v1\authorization\models
 * @property integer $id
 * @property string $password
 * @property string $password_reset_token
 * @property string $email
 * @property string $auth_key
 * @property string $source
 * @property string $source_id
 * @property string $phone_number
 * @property integer $terms_condition
 * @property integer $created_at
 * @property integer $updated_at
 */
class RestUserEntity extends User
{
    use SocialRepository, AuthorizationJwt, AuthorizationRepository;

    const ROLE_USER  = 'user';
    const ROLE_GUEST = 'guest';

    const SCENARIO_REGISTER        = 'register';
    const SCENARIO_RECOVERY_PWD    = 'recovery-password';
    const SCENARIO_UPDATE_PASSWORD = 'update-password';
    const SCENARIO_LOGIN           = 'login';

    const FB     = 'fb';
    const VK     = 'vk';
    const GMAIL  = 'gmail';
    const NATIVE = 'native';

    public $confirm_password;
    public $current_password;
    public $new_password;
    public $role;
    
    /**
     * @return string
     */
    public static function tableName(): string 
    {
        return '{{%user}}';
    }

    /**
     * @return array
     */
    public function attributeLabels(): array
    {
        return [
            'id'               => '#',
            'email'            => 'Email',
            'phone_number'     => 'Номер телефона',
            'source'           => 'Социальная сеть',
            'source_id'        => 'Пользователь в социальной сети',
            'terms_condition'  => 'Пользовательское соглашение',
            'password'         => 'Пароль',
            'new_password'     => 'Новый пароль',
            'current_password' => 'Текущий пароль',
            'created_at'       => 'Дата создания',
            'updated_at'       => 'Дата изменения',
        ];
    }

    /**
     * @return array
     */
    public function scenarios(): array
    {
        $scenarios = parent::scenarios();

        $scenarios[self::SCENARIO_REGISTER] = [
            'email', 'password', 'phone_number', 'terms_condition', 'source', 'source_id', 'confirm_password', 'role'
        ];

        $scenarios[self::SCENARIO_RECOVERY_PWD] = [
            'email', 'password', 'confirm_password', 'phone_number', 'source', 'source_id'
        ];

        $scenarios[self::SCENARIO_LOGIN] = ['email', 'password', 'phone_number',];

        $scenarios[self::SCENARIO_UPDATE_PASSWORD] = ['current_password', 'password', 'confirm_password', 'new_password'];

        return $scenarios;
    }

    /**
     * @return array
     */
    public function behaviors(): array
    {
        $behaviors = parent::behaviors();

        $behaviors['timestampBehavior'] = TimestampBehavior::className();
        $behaviors['responseBehavior'] = ResponseBehavior::className();
        $behaviors['validationExceptionFirstMessage'] = ValidationExceptionFirstMessage::className();

        return $behaviors;
    }

    /**
     * @return array
     */
    public function rules(): array 
    {
        return [
            ['email', 'email'],
            ['role', 'in', 'range' => [self::ROLE_GUEST, self::ROLE_USER]],
            [['email', 'phone_number'], 'unique', 'on' => self::SCENARIO_REGISTER],
            [
                'email',
                'required',
                'when' => function (User $model) {
                    return empty($model->phone_number);
                },
                'message' => 'Необходимо заполнить «Email» или «Номер телефона».',
                'on' => [self::SCENARIO_REGISTER, self::SCENARIO_LOGIN,]
            ],
            [
                'phone_number',
                'required',
                'when' => function (User $model) {
                    return empty($model->email);
                },
                'message' => 'Необходимо заполнить «Email» или «Номер телефона».',
                'on' => [self::SCENARIO_REGISTER, self::SCENARIO_LOGIN,]
            ],
            [
                'terms_condition',
                'required',
                'on'            => self::SCENARIO_REGISTER,
                'requiredValue' => 1,
                'message'       => Yii::t('app', 'Вы должны принять "Пользовательские соглашения."')
            ],
            ['password', 'string', 'min' => 6, 'on' => [self::SCENARIO_REGISTER,]],
            [
                'current_password',
                'validateCurrentPassword',
                'on' => [self::SCENARIO_UPDATE_PASSWORD,]],
            [
                ['current_password', 'new_password', 'confirm_password'],
                'required',
                'on' => self::SCENARIO_UPDATE_PASSWORD,
            ],
            ['new_password', 'string', 'min' => 6, 'on' => [self::SCENARIO_UPDATE_PASSWORD,]],
            [
                'confirm_password',
                'compare',
                'compareAttribute' => 'new_password',
                'on'               => [self::SCENARIO_UPDATE_PASSWORD]
            ],
            [
                ['password', 'confirm_password'],
                'required',
                'on' => [self::SCENARIO_REGISTER,]
            ],
            ['password', 'required', 'on' => self::SCENARIO_LOGIN],
            [
                'confirm_password',
                'compare',
                'compareAttribute' => 'password',
                'on'               => [self::SCENARIO_REGISTER, self::SCENARIO_RECOVERY_PWD]
            ],
            [['source', 'source_id', 'phone_number'], 'string'],
            ['source', 'in', 'range' => [self::FB, self::VK, self::GMAIL, self::NATIVE]],
            ['phone_number', 'string', 'max' => 20],
            [['created_at', 'updated_at'], 'safe'],
        ];
    }

    /**
     * @param bool $insert
     * @return bool
     * @throws \yii\base\Exception
     */
    public function beforeSave($insert): bool
    {
        parent::beforeSave($insert);

        if (
            $this->scenario === self::SCENARIO_REGISTER
            || $this->scenario === self::SCENARIO_RECOVERY_PWD
            || $this->scenario === self::SCENARIO_UPDATE_PASSWORD
        ) {
            $this->auth_key = Yii::$app->security->generateRandomString();
            $this->password_reset_token = Yii::$app->security->generateRandomString() . '_' . time();

            if ($this->source == self::NATIVE) {
                $this->password = Yii::$app->security->generatePasswordHash($this->password);
            } else {
                $this->password = Yii::$app->security->generateRandomString(32);
            }
        }

        return true;
    }

    /**
     * @param bool $insert
     * @param array $changedAttributes
     */
    public function afterSave($insert, $changedAttributes)
    {
        parent::afterSave($insert, $changedAttributes);

        if ($this->scenario == self::SCENARIO_REGISTER) {
            $this->role = self::ROLE_USER;
            $userRole = Yii::$app->authManager->getRole($this->role);
            Yii::$app->authManager->assign($userRole, $this->getId());
        }

    }

    /**
     * Check User current password
     * @param $attribute
     * @return bool
     */
    public function validateCurrentPassword($attribute)
    {
        if (!Yii::$app->security->validatePassword($this->{$attribute}, $this->password)) {
            $this->addError($this->{$attribute}, Yii::t('app', 'Неверно введен старый пароль.'));
            return false;
        }

        return true;
    }
}
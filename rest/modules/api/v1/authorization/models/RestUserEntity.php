<?php

namespace rest\modules\api\v1\authorization\models;

use rest\modules\api\v1\authorization\models\repositories\AuthorizationJwt;
use rest\modules\api\v1\authorization\models\repositories\AuthorizationRepository;
use yii\behaviors\TimestampBehavior;
use common\models\user\User;
use rest\modules\api\v1\authorization\models\repositories\SocialRepository;
use Yii;

/**
 * Class RestUserEntity
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

    const SCENARIO_REGISTER        = 'register';
    const SCENARIO_RECOVERY_PWD    = 'recovery-password';
    const SCENARIO_UPDATE_PASSWORD = 'update-password';
    const SCENARIO_LOGIN           = 'login';

    const FB     = 'fb';
    const VK     = 'vk';
    const GMAIL  = 'gmail';
    const NATIVE = 'native';

    public $confirm_password;
    
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
            'id'              => '#',
            'email'           => 'Email',
            'phone_number'    => 'Номер телефона',
            'source'          => 'Социальная сеть',
            'source_id'       => 'Пользователь в социальной сети',
            'terms_condition' => 'Пользовательское соглашение',
            'password'        => 'Пароль',
            'created_at'      => 'Дата создания',
            'updated_at'      => 'Дата изменения',
        ];
    }

    /**
     * @return array
     */
    public function scenarios(): array
    {
        $scenarios = parent::scenarios();

        $scenarios[self::SCENARIO_REGISTER] = [
            'email', 'password', 'phone_number', 'terms_condition', 'source', 'source_id', 'confirm_password'
        ];

        $scenarios[self::SCENARIO_RECOVERY_PWD] = [
            'email', 'password', 'confirm_password', 'phone_number', 'source', 'source_id'
        ];

        $scenarios[self::SCENARIO_LOGIN] = [
            'email', 'password', 'phone_number',
        ];

        return $scenarios;
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
    public function rules(): array 
    {
        return [
            ['email', 'email'],
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
                'message'       => Yii::t('app', 'Вы должны принять "Пользовательские соглашения"')
            ],
            ['password', 'string', 'min' => 6, 'on' => [self::SCENARIO_REGISTER,]],
            [['password', 'confirm_password'], 'required', 'on' => [self::SCENARIO_REGISTER,]],
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

        if ($this->scenario === self::SCENARIO_REGISTER || $this->scenario === self::SCENARIO_RECOVERY_PWD) {
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
}
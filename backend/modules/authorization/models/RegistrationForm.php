<?php
namespace backend\modules\authorization\models;

use borales\extensions\phoneInput\PhoneInputValidator;
use common\models\user\User;
use yii\base\Model;
use Yii;

class RegistrationForm extends Model
{
    public $authorizationModel = User::class;
    public $email;
    public $phone_number;
    public $name;
    public $last_name;
    public $role;
    public $password;
    public $confirm_password;

    const SCENARIO_PASSWORD_CREATE = 'password_create';
    const SCENARIO_MANAGER_CREATE  = 'manager_create';
    const ROLE_ADMIN   = 'ADMIN';
    const ROLE_MANAGER = 'MANAGER';

    public function scenarios()
    {
        $scenarios = parent::scenarios();

        $scenarios[self::SCENARIO_PASSWORD_CREATE] = [
            'password', 'confirm_password'
        ];
        $scenarios[self::SCENARIO_MANAGER_CREATE] = [
            'email', 'name', 'last_name', 'phone_number'
        ];
        return $scenarios;
    }

    public function rules()
    {
        return [
            [['email', 'name', 'last_name', 'phone_number'], 'required', 'on' => self::SCENARIO_MANAGER_CREATE ],
            ['phone_number', PhoneInputValidator::class],
            [['email'], 'email'],
            ['email', 'unique', 'targetClass' => User::class, 'message' => Yii::t('app', 'This email address has already been taken.')],
            ['phone_number', 'unique', 'targetClass' => User::class, 'message' => Yii::t('app', 'This phone number has already been taken.')],
            [['name', 'last_name', 'phone_number',], 'string', 'max' => 20],
            [['password'], 'string', 'min' => 6],
            [
                'confirm_password',
                'compare',
                'compareAttribute' => 'password',
            ],
            [['created_at', 'updated_at'], 'safe'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'email'                 => Yii::t('app', 'E-mail'),
            'password'              => Yii::t('app', 'Password'),
            'confirm_password'      => Yii::t('app', 'Repeat Password'),
            'created_at'            => Yii::t('app', 'Created At'),
            'updated_at'            => Yii::t('app', 'Updated At'),
            'name'                  => Yii::t('app', 'First Name'),
            'last_name'             => Yii::t('app', 'Last Name'),
            'role'                  => Yii::t('app', 'Role'),
            'phone_number'          => Yii::t('app', 'Phone Number'),
        ];
    }

    /**
     * Login user in a system
     * @return bool
     */
    public function registry()
    {
        if ($this->validate()) {
            return \Yii::$app->user->login(User::findByPhoneNumber($this->phone_number), $this->rememberMe ? 3600 * 24 * 30 : 0);
        } else {
            return false;
        }
    }
}

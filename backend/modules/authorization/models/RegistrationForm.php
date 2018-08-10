<?php
namespace backend\modules\authorization\models;

use borales\extensions\phoneInput\PhoneInputValidator;
use common\models\user\User;
use yii\base\Model;

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
        return $scenarios;
    }

    public function rules()
    {
        return [
            [['email', 'name', 'last_name', 'password', 'confirm_password', 'phone_number'], 'required' ],
            ['phone_number', PhoneInputValidator::class],
            [['email'], 'email'],
            ['email', 'unique', 'targetClass' => User::class, 'message' => 'This email address has already been taken.'],
            ['phone_number', 'validatePhoneNumber'],
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
            'email'                 => 'Email',
            'password'              => 'Пароль',
            'confirm_password'      => 'Подтверждение пароля',
            'created_at'            => 'Дата создания',
            'updated_at'            => 'Дата изменения',
            'name'                  => 'Имя пользователя',
            'last_name'             => 'Фамилия пользователя',
            'role'                  => 'Должность',
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

    /**
     * Check phone_number on the unique value
     *
     * @param $attribute
     */
    public function validatePhoneNumber($attribute)
    {
        if (!$this->hasErrors()) {
            $email = $this->getPhoneNumber();
            if ($email) {
                $this->addError($attribute, 'Данный номер телефона уже зарегистрирован');
            }
        }
    }

    /**
     * Getting phone_number for validate
     *
     * @return User
     */
    public function getPhoneNumber()
    {
        return User::findOne(['phone_number' => $this->phone_number]);
    }
}

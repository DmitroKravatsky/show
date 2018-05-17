<?php
namespace backend\modules\authorization\models;

use borales\extensions\phoneInput\PhoneInputValidator;
use common\models\user\User;
use yii\base\Model;

class RegistrationForm extends Model
{
    public $authorizationModel = User::class;
    public $email;
    public $name;
    public $last_name;
    public $role;
    public $password;
    public $confirm_password;

    const SCENARIO_PASSWORD_CREATE = 'password_create';
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

            [['email', 'name', 'last_name', 'role', 'password', 'confirm_password'], 'required'],
            ['email', 'validateEmail'],
            [['name', 'last_name',], 'string', 'max' => 20],
            [
                'confirm_password',
                'compare',
                'compareAttribute' => 'password',
            ],
            [['created_at', 'updated_at'], 'safe'],
            ['role', 'in', 'range' => [self::ROLE_ADMIN, self::ROLE_MANAGER]],

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
            'last_name'             => 'Код востановления',
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
     * Check email on the unique value
     *
     * @param $attribute
     */
    public function validateEmail($attribute)
    {
        if (!$this->hasErrors()) {
            $email = $this->getEmail();
            if ($email) {
                $this->addError($attribute, 'Данный E-mail уже зарегистрирован');
            }
        }
    }

    /**
     * Getting email for validate
     *
     * @return User
     */
    public function getEmail()
    {
        return User::findOne(['email' => $this->email]);
    }





}
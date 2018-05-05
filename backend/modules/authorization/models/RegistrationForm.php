<?php
namespace backend\modules\authorization\models;

use borales\extensions\phoneInput\PhoneInputValidator;
use common\models\user\User;
use yii\base\Model;

class RegistrationForm extends Model
{
    public $email;
    public $name;
    public $last_name;
    public $role;
    public $password;
    public $confirm_password;

    const ROLE_ADMIN   = 'ADMIN';
    const ROLE_MANAGER = 'MANAGER';

    public function rules()
    {
        return [

            [['email', 'name', 'last_name', 'role', 'password', 'confirm_password'], 'required'],
            [['phone_number'], PhoneInputValidator::class],
            ['email', 'email'],
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
            'role'                  => 'Токен обновления',
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
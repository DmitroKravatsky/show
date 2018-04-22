<?php
namespace backend\modules\authorization\models;

use borales\extensions\phoneInput\PhoneInputValidator;
use common\models\user\User;
use yii\base\Model;

class LoginForm extends Model
{
    public $phone_number;
    public $password;
    public $rememberMe;

    public function rules()
    {
        return [

            [['phone_number', 'password'], 'required'],
            [['phone_number'], PhoneInputValidator::class],

        ];
    }

    public function attributeLabels()
    {
        return [
            'phone_number'      => 'Номер телефона',
            'password'          => 'Пароль',
            'rememberMe'        => 'Запомнить меня',
        ];
    }

    /**
     * Login user in a system
     * @return bool
     */
    public function login()
    {
        if ($this->validate()) {
            return \Yii::$app->user->login(User::findByPhoneNumber($this->phone_number), $this->rememberMe ? 3600 * 24 * 30 : 0);
        } else {
            return false;
        }
    }


}
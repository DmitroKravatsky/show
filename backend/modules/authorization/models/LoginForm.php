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
            $user = User::findByPhoneNumber($this->phone_number);
            if ($user && $this->validatePassword($this->password, $user->password)) {
                return \Yii::$app->user->login($user, $this->rememberMe ? \Yii::$app->params['LoginDuration'] : 0);
            }
        } else {
            return false;
        }
    }

    /**
     * @param string $invite_code
     * @return bool
     */
    public function loginByInvite($invite_code):bool
    {
        if ($user = User::findOne(['invite_code' => $invite_code, 'invite_code_status' => 'ACTIVE'])) {
            $user->invite_code_status = 'INACTIVE';
            if ($user->save(false)) {
                \Yii::$app->user->login($user, \Yii::$app->params['LoginDuration']);
                return true;
            }
        }
        return false;
    }

    /**
     * Compares password from form with password from db
     * @param $inputPassword
     * @param $currentPassword
     * @return bool
     */
    public function validatePassword($inputPassword, $currentPassword): bool
    {
        if (\Yii::$app->security->validatePassword($inputPassword, $currentPassword)) {
            return true;
        } else {
            $this->addError('password', \Yii::t('app', 'Password is incorrect'));
            return false;
        }
    }

}

<?php
namespace backend\modules\authorization\models;

use backend\models\BackendUser;
use common\models\user\User;
use yii\base\Model;
use Yii;

class LoginForm extends Model
{
    public $email;
    public $password;
    public $rememberMe;

    public function rules()
    {
        return [

            [['email', 'password'], 'required'],
            [['email'], 'email',],
            [['password'], 'validatePassword']

        ];
    }

    public function attributeLabels()
    {
        return [
            'email'             => Yii::t('app', 'E-mail'),
            'password'          => Yii::t('app', 'Password'),
            'rememberMe'        => Yii::t('app', 'Remember me'),
        ];
    }

    /**
     * Login user in a system
     * @return bool
     */
    public function login(): bool
    {
        if ($user = User::findByEmail($this->email)) {
            return Yii::$app->user->login($user, $this->rememberMe ? Yii::$app->params['LoginDuration'] : 0);
        }
        return false;
    }

    /**
     * @param string $invite_code
     * @return bool
     */
    public function loginByInvite($invite_code):bool
    {
        if ($user = User::findOne(['invite_code' => $invite_code, 'invite_code_status' => BackendUser::STATUS_INVITE_ACTIVE])) {
            $user->invite_code_status = BackendUser::STATUS_INVITE_INACTIVE;
            if ($user->save(false, ['invite_code_status'])) {
                \Yii::$app->user->login($user, \Yii::$app->params['LoginDuration']);
                return true;
            }
        }
        return false;
    }

    /**
     * Compares password from form with password from db
     */
    public function validatePassword()
    {
        if (!($user = User::findByEmail($this->email)) || !Yii::$app->security->validatePassword($this->password, $user->password)) {
            $this->addError('password', Yii::t('app', 'Incorrect email or password.'));
        }
    }
}

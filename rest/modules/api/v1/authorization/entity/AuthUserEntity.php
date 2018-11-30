<?php

declare(strict_types=1);

namespace rest\modules\api\v1\authorization\entity;

use common\models\bid\BidEntity;
use common\models\userProfile\UserProfileEntity;
use common\behaviors\ValidationExceptionFirstMessage;
use common\models\user\User;
use rest\modules\api\v1\authorization\models\repositories\AuthorizationRepository;
use yii\web\NotFoundHttpException;
use yii\web\ServerErrorHttpException;
use yii\web\UnprocessableEntityHttpException;

/**
 * @package rest\modules\api\v1\authorization\models
 * @property integer $id
 * @property string $password
 * @property string $confirm_password
 * @property string $password_reset_token
 * @property string $email
 * @property string $new_email
 * @property string $auth_key
 * @property string $source
 * @property string $phone_number
 * @property integer $terms_condition
 * @property string $refresh_token
 * @property integer $created_refresh_token
 * @property integer $created_at
 * @property integer $updated_at
 * @property string $recovery_code
 * @property integer $created_recovery_code
 * @property integer $status
 * @property integer $verification_code
 * @property integer $email_verification_code
 * @property integer $created_email_verification_code
 * @property integer $phone_verification_code
 * @property integer $created_phone_verification_code
 */

class AuthUserEntity extends User
{
    use AuthorizationJwt, AuthorizationRepository;

    const SCENARIO_REGISTER_BY_BID               = 'register-by-bid';
    const SCENARIO_UPDATE_BY_BID                 = 'update-by-bid';
    const SCENARIO_SOCIAL_REGISTER               = 'social_register';
    const SCENARIO_UPDATE_PASSWORD               = 'update-password';
    const SCENARIO_SEND_EMAIL_VERIFICATION_CODE  = 'send-email-verification-code';
    const SCENARIO_VERIFY_NEW_EMAIL              = 'verify-email';
    const SCENARIO_SEND_PHONE_VERIFICATION_CODE  = 'send-phone-verification-code';
    const SCENARIO_VERIFY_NEW_PHONE              = 'verify-phone';

    const REGISTER_BY_BID_NO = 0;
    const REGISTER_BY_BID_YES = 1;

    public $confirm_password;
    public $current_password;
    public $new_password;
    public $role;
    public $terms_condition;

    public function scenarios(): array
    {
        $scenarios = parent::scenarios();

        $scenarios[self::SCENARIO_REGISTER_BY_BID] = ['email', 'password', 'phone_number', 'source', 'register_by_bid',];

        $scenarios[self::SCENARIO_UPDATE_BY_BID] = ['email', 'phone_number',];

        $scenarios[self::SCENARIO_SOCIAL_REGISTER] = [
            'email', 'password', 'phone_number', 'terms_condition', 'source', 'role',
            'refresh_token', 'created_refresh_token', 'verification_code'
        ];

        $scenarios[self::SCENARIO_UPDATE_PASSWORD] = ['current_password', 'password', 'confirm_password', 'new_password'];

        $scenarios[self::SCENARIO_SEND_EMAIL_VERIFICATION_CODE]  = ['email'];

        $scenarios[self::SCENARIO_VERIFY_NEW_EMAIL]  = ['email', 'email_verification_code'];

        $scenarios[self::SCENARIO_SEND_PHONE_VERIFICATION_CODE]  = ['phone_number'];

        $scenarios[self::SCENARIO_VERIFY_NEW_PHONE]  = ['phone_number', 'phone_verification_code'];

        return $scenarios;
    }

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
            'id'                              => '#',
            'email'                           => 'Email',
            'phone_number'                    => 'Номер телефона',
            'source'                          => 'Социальная сеть',
            'terms_condition'                 => 'Пользовательское соглашение',
            'password'                        => 'Пароль',
            'confirm_password'                => 'Подтверждение пароля',
            'new_password'                    => 'Новый пароль',
            'current_password'                => 'Текущий пароль',
            'created_at'                      => 'Дата создания',
            'updated_at'                      => 'Дата изменения',
            'created_recovery_code'           => 'Дата создания кода востановления',
            'recovery_code'                   => 'Код востановления',
            'refresh_token'                   => 'Токен обновления',
            'created_refresh_token'           => 'Дата создания токена доступа',
            'status'                          => 'Статус полльзователя',
            'verification_code'               => 'Код подтверждения аккаунта',
            'phone_verification_code'         => 'Код подтверждения телефона',
            'created_phone_verification_code' => 'Дата создания кода верификации телефона',
        ];
    }

    /**
     * @return array
     */
    public function behaviors(): array
    {
        $behaviors = parent::behaviors();

        $behaviors['validationExceptionFirstMessage'] = ValidationExceptionFirstMessage::class;

        return $behaviors;
    }

    /**
     * Check User current password
     * @param $attribute
     * @return bool
     */
    public function validateCurrentPassword($attribute)
    {
        if (!\Yii::$app->security->validatePassword($this->{$attribute}, $this->password)) {
            $this->addError($this->{$attribute}, \Yii::t('app', 'Неверно введен старый пароль.'));
            return false;
        }

        return true;
    }

    /**
     * Get user's role by Id
     *
     * @param $userId
     * @return mixed
     */
    public function getUserRole($userId)
    {
        return current(\Yii::$app->authManager->getRolesByUser($userId))->name;
    }

    /**
     * Get user profile info
     * @return \yii\db\ActiveQuery
     */
    public function getUserProfile()
    {
        return $this->hasOne(UserProfileEntity::class, ['user_id' => 'id']);
    }

    /**
     * Checks if user have at least one bid
     * @return bool
     */
    public function hasBids():bool
    {
        return BidEntity::find()->where(['created_by' => \Yii::$app->user->id])->exists();
    }

    /**
     * Sends verification code to new email
     * @param $email string potential user new email
     * @return bool
     * @throws NotFoundHttpException
     * @throws ServerErrorHttpException
     */
    public function sendEmailVerificationCode($email)
    {
        $this->setAttribute('email', $email);
        $this->setScenario(self::SCENARIO_SEND_EMAIL_VERIFICATION_CODE);
        if (!$this->validate('email')) {
            $this->throwModelException($this->errors);
        }

        $user = static::findOne(\Yii::$app->user->id);
        if (!$user) {
            throw new NotFoundHttpException('User is not found');
        }

        $user->email_verification_code = rand(1000, 9999);
        $user->created_email_verification_code = time();
        $user->new_email = $email;

        if ($user->save(false)) {
            \Yii::$app->sendMail->run(
                'sendEmailVerificationCode-html.php',
                [
                    'email' => $email,
                    'verificationCode' => $user->email_verification_code
                ],
                \Yii::$app->params['supportEmail'], $email, 'Email validation'
            );
            return true;
        }
        throw new ServerErrorHttpException();
    }

    /**
     * Updates user email if verification code is correct
     * @param $params
     * @return bool
     * @throws NotFoundHttpException
     * @throws ServerErrorHttpException
     * @throws UnprocessableEntityHttpException
     */
    public function verifyNewEmail($params)
    {
        $this->setAttributes([
            'email' => $params['email'],
            'email_verification_code' => $params['email_verification_code']
        ]);
        $this->setScenario(self::SCENARIO_VERIFY_NEW_EMAIL);
        if (!$this->validate()) {
            $this->throwModelException($this->errors);
        }

        $user = static::findOne(\Yii::$app->user->id);
        if (!$user) {
            throw new NotFoundHttpException('User is not found');
        }

        if (!$this->isNewEmailVerificationValid($params['email_verification_code'], $user)) {
            throw new UnprocessableEntityHttpException('Verification code is invalid or expired');
        }

        if (!$this->isNewEmailValid($params['email'], $user)) {
            throw new UnprocessableEntityHttpException('Email address is invalid.');
        }

        $user->email = $params['email'];
        $user->email_verification_code = null;
        $user->new_email = null;

        if ($user->save(false)) {
            return true;
        }
        throw new ServerErrorHttpException('Server error, please again try later');
    }

    /**
     * Validates passed verification code
     * @param $verificationCode string Code that user has passed
     * @param $userModel AuthUserEntity
     * @return bool
     */
    public function isNewEmailVerificationValid($verificationCode, AuthUserEntity $userModel):bool
    {
        if (($userModel->created_email_verification_code + \Yii::$app->params['emailVerificationCodeLifeTime']) > time()) {
            if (intval($verificationCode) === $userModel->email_verification_code) {
                return true;
            }
        }
        return false;
    }

    public function isNewEmailValid(string $email, AuthUserEntity $userModel): bool
    {
        return $email === $userModel->new_email;
    }

    /**
     * Sends verification code to new phone number
     * @param $phoneNumber string potential user new phone
     * @return bool
     * @throws NotFoundHttpException
     * @throws ServerErrorHttpException
     */
    public function sendPhoneVerificationCode($phoneNumber)
    {
        $this->setAttribute('phone_number', $phoneNumber);
        $this->setScenario(self::SCENARIO_SEND_PHONE_VERIFICATION_CODE);
        if (!$this->validate('phone_number')) {
            $this->throwModelException($this->errors);
        }

        $user = static::findOne(\Yii::$app->user->id);
        if (!$user) {
            throw new NotFoundHttpException('User is not found');
        }

        $user->phone_verification_code = 0000;//rand(1000, 9999);
        $user->created_phone_verification_code = time();
        if ($user->save(false)) {
            //\Yii::$app->sendSms->run('Your verification code is ' . $user->phone_verification_code, $phone_number);
            return true;
        }

        throw new ServerErrorHttpException();
    }

    /**
     * Updates user phone number if verification code is correct
     * @param $params
     * @return bool
     * @throws NotFoundHttpException
     * @throws ServerErrorHttpException
     * @throws UnprocessableEntityHttpException
     */
    public function verifyNewPhone($params)
    {
        $this->setAttributes([
            'phone_number' => $params['phone_number'],
            'phone_verification_code' => $params['phone_verification_code']
        ]);
        $this->setScenario(self::SCENARIO_VERIFY_NEW_PHONE);
        if (!$this->validate()) {
            $this->throwModelException($this->errors);
        }

        $user = static::findOne(\Yii::$app->user->id);
        if (!$user) {
            throw new NotFoundHttpException('User is not found');
        }

        if (!$this->isNewPhoneVerificationCodeValid($params['phone_verification_code'], $user)) {
            throw new UnprocessableEntityHttpException('Verification code is invalid or expired');
        }

        $user->phone_number = $params['phone_number'];
        $user->phone_verification_code = null;

        if ($user->save(false)) {
            return true;
        }
        throw new ServerErrorHttpException('Server error, please try later');
    }

    /**
     * Validates passed verification code
     * @param $verificationCode string Code that user has passed
     * @param $userModel AuthUserEntity
     * @return bool
     */
    public function isNewPhoneVerificationCodeValid($verificationCode, AuthUserEntity $userModel):bool
    {
        if (!($userModel->created_phone_verification_code + \Yii::$app->params['phoneVerificationCodeLifeTime']) > time()) {
            return false;
        }
        if (intval($verificationCode) === $userModel->phone_verification_code) {
            return true;
        }
        return false;
    }
}

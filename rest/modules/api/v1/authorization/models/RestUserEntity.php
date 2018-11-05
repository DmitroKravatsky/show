<?php

namespace rest\modules\api\v1\authorization\models;

use borales\extensions\phoneInput\PhoneInputValidator;
use common\models\bid\BidEntity;
use common\models\userProfile\UserProfileEntity;
use common\behaviors\ValidationExceptionFirstMessage;
use rest\modules\api\v1\authorization\models\repositories\AuthorizationJwt;
use rest\modules\api\v1\authorization\models\repositories\AuthorizationRepository;
use yii\base\Exception;
use common\models\user\User;
use rest\modules\api\v1\authorization\models\repositories\SocialRepository;
use yii\web\ErrorHandler;
use yii\web\HttpException;
use yii\web\NotFoundHttpException;
use yii\web\ServerErrorHttpException;
use yii\db\Exception as ExceptionDb;
use yii\web\UnprocessableEntityHttpException;

/**
 * Class RestUserEntity
 *
 * @mixin ValidationExceptionFirstMessage
 *
 * @package rest\modules\api\v1\authorization\models
 * @property integer $id
 * @property string $password
 * @property string $confirm_password
 * @property string $password_reset_token
 * @property string $email
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

class RestUserEntity extends User
{
    use SocialRepository, AuthorizationJwt, AuthorizationRepository;

    const SCENARIO_REGISTER                      = 'register';
    const SCENARIO_REGISTER_BY_BID               = 'register-by-bid';
    const SCENARIO_UPDATE_BY_BID                 = 'update-by-bid';
    const SCENARIO_SOCIAL_REGISTER               = 'social_register';
    const SCENARIO_RECOVERY_PWD                  = 'recovery-password';
    const SCENARIO_UPDATE_PASSWORD               = 'update-password';
    const SCENARIO_LOGIN                         = 'login';
    const SCENARIO_VERIFY_PROFILE                = 'verify';
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
    public function scenarios(): array
    {
        $scenarios = parent::scenarios();

        $scenarios[self::SCENARIO_REGISTER] = [
            'email', 'password', 'phone_number', 'terms_condition', 'source', 'confirm_password', 'role',
            'refresh_token', 'created_refresh_token', 'verification_code'
        ];

        $scenarios[self::SCENARIO_REGISTER_BY_BID] = ['email', 'password', 'phone_number', 'source', 'register_by_bid',];

        $scenarios[self::SCENARIO_UPDATE_BY_BID] = ['email', 'phone_number',];

        $scenarios[self::SCENARIO_SOCIAL_REGISTER] = [
            'email', 'password', 'phone_number', 'terms_condition', 'source', 'role',
            'refresh_token', 'created_refresh_token', 'verification_code'
        ];

        $scenarios[self::SCENARIO_RECOVERY_PWD] = [
            'password', 'confirm_password', 'phone_number','recovery_code'
        ];

        $scenarios[self::SCENARIO_LOGIN] = ['email', 'password', 'phone_number'];

        $scenarios[self::SCENARIO_UPDATE_PASSWORD] = ['current_password', 'password', 'confirm_password', 'new_password'];

        $scenarios[self::SCENARIO_VERIFY_PROFILE]  = ['verification_code', 'phone_number'];

        $scenarios[self::SCENARIO_SEND_EMAIL_VERIFICATION_CODE]  = ['email'];

        $scenarios[self::SCENARIO_VERIFY_NEW_EMAIL]  = ['email', 'email_verification_code'];

        $scenarios[self::SCENARIO_SEND_PHONE_VERIFICATION_CODE]  = ['phone_number'];

        $scenarios[self::SCENARIO_VERIFY_NEW_PHONE]  = ['phone_number', 'phone_verification_code'];

        return $scenarios;
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
     * @return array
     */
    public function rules(): array 
    {
        return [
            ['email', 'email'],
            ['email', 'required', 'on' => self::SCENARIO_SEND_EMAIL_VERIFICATION_CODE],
            [['email_verification_code', 'email'], 'required', 'on' => self::SCENARIO_VERIFY_NEW_EMAIL],
            ['phone_number', 'required', 'on' => self::SCENARIO_SEND_PHONE_VERIFICATION_CODE],
            [['phone_verification_code', 'phone_number'], 'required', 'on' => self::SCENARIO_VERIFY_NEW_PHONE],
            [['verification_code', 'register_by_bid', 'email_verification_code', 'phone_verification_code'], 'integer'],
            ['role', 'in', 'range' => [self::ROLE_GUEST, self::ROLE_USER]],
            [
                'phone_number',
                'required',
                'on' => [
                    self::SCENARIO_REGISTER, self::SCENARIO_LOGIN, self::SCENARIO_RECOVERY_PWD,
                    self::SCENARIO_VERIFY_PROFILE, self::SCENARIO_REGISTER_BY_BID,
                ]
            ],
            [
                'terms_condition',
                'required',
                'on'            => [self::SCENARIO_REGISTER, self::SCENARIO_SOCIAL_REGISTER],
                'requiredValue' => 1,
                'message'       => \Yii::t('app', 'Вы должны принять "Пользовательские соглашения."')
            ],
            ['password', 'string', 'min' => 6, 'on' => [self::SCENARIO_REGISTER, self::SCENARIO_SOCIAL_REGISTER],],
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
                'on' => [self::SCENARIO_REGISTER]
            ],
            ['password', 'required', 'on' => self::SCENARIO_LOGIN],
            [
                ['phone_number', 'password', 'confirm_password','recovery_code'],
                'required',
                'on' => [self::SCENARIO_RECOVERY_PWD,]
            ],
            [
                'confirm_password',
                'compare',
                'compareAttribute' => 'password',
                'on'               =>
                    [
                        self::SCENARIO_REGISTER,
                        self::SCENARIO_SOCIAL_REGISTER,
                        self::SCENARIO_RECOVERY_PWD
                    ]

            ],
            [['source', 'phone_number'], 'string'],
            ['source', 'in', 'range' => [self::SOCIAL, self::NATIVE]],
            ['phone_number', 'string', 'max' => 20],
            [['phone_number'], PhoneInputValidator::class, 'region' => ['RU', 'UA', 'BY']],
            [['created_at', 'updated_at', 'refresh_token', 'status'], 'safe'],
            ['verification_code', 'required', 'on' => [self::SCENARIO_VERIFY_PROFILE]],
            [['recovery_code'], 'string', 'max' => 4],
            [
                ['phone_number', 'email',],
                'unique',
                'on' => [
                    self::SCENARIO_REGISTER, self::SCENARIO_SOCIAL_REGISTER, self::SCENARIO_REGISTER_BY_BID, self::SCENARIO_UPDATE_BY_BID
                ]
            ],
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

        $this->defaultBeforeSavePropertiesPreProcess();

        if ($this->scenario === self::SCENARIO_SOCIAL_REGISTER) {
            $this->status = self::STATUS_VERIFIED;
        }

        return true;
    }

    /**
     * Reserts recovery code after recovery password
     *
     * @return int
     */
    public function resetRecoveryCode()
    {
        return \Yii::$app->db->createCommand()
            ->update(self::tableName(), [
                'recovery_code'         => null,
                'created_recovery_code' => null
            ], ['id' => $this->getId()])->execute();
    }


    /**
     * @param bool $insert
     * @param array $changedAttributes
     * @throws \Exception
     */
    public function afterSave($insert, $changedAttributes)
    {
        parent::afterSave($insert, $changedAttributes);

        $scenarios = [self::SCENARIO_REGISTER, self::SCENARIO_SOCIAL_REGISTER, self::SCENARIO_REGISTER_BY_BID];
        if ($insert && in_array($this->scenario, $scenarios)) {
            $this->role = self::ROLE_USER;
            $userRole = \Yii::$app->authManager->getRole($this->role);
            \Yii::$app->authManager->assign($userRole, $this->getId());
        }
        if ($this->scenario === self::SCENARIO_RECOVERY_PWD) {
            $this->resetRecoveryCode();
        }

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
     * Check user by email and get his data
     *
     * @param $email
     * @return RestUserEntity
     * @throws ServerErrorHttpException
     */
    public function getUserByEmail($email)
    {
        if (empty($restUser = RestUserEntity::findOne(['email' => $email]))) {
            throw new ServerErrorHttpException('Пользователя с таким email не существует, 
            пройдите процедуру регистрации.');
        }

        return $restUser;
    }

    /**
     * Recovery user`s password
     *
     * @param $postData
     * @return bool
     * @throws HttpException
     * @throws ServerErrorHttpException
     */
    public function recoveryCode($postData)
    {
        $recoveryCode = $this->recovery_code;
        $createdRecoveryCode = $this->created_recovery_code;
        try{
            $this->setAttributes(
                [
                    'password' => $postData['password'],
                    'confirm_password' => $postData['confirm_password'],
                    'recovery_code' => $postData['recovery_code'],
                ]
            );
            if ($this->validate()
                && $this->checkRecoveryCode($recoveryCode, $createdRecoveryCode, $postData['recovery_code'])
            ) {
                return $this->save();
            }
            $this->validationExceptionFirstMessage($this->errors);
        } catch (ExceptionDb $e) {
            throw new UnprocessableEntityHttpException($e->getMessage());
        } catch (Exception $e) {
            \Yii::error(ErrorHandler::convertExceptionToString($e));
            throw new ServerErrorHttpException('Произошла ошибка при восстановлении пароля.');
        }
    }

    /**
     * Validate user recovery code
     *
     * @param $recoveryCode
     * @param $createdRecoveryCode
     * @param $postData
     * @return bool
     */
    public function checkRecoveryCode($recoveryCode, $createdRecoveryCode, $postData)
    {
        if ($recoveryCode != $postData) {
            $this->addError('recovery_code', 'Код восстановления неверен!');
            return false;
        } elseif (!$createdRecoveryCode || $createdRecoveryCode + 3600 < time()) {
            $this->addError('created_recovery_code', 'Время кода восстановления истекло. Сгенерируйте новый!');
            return false;
        }
        return true;
    }

    /**
     * Method adds token if it isn there yet
     * @param $token
     * @return bool
     */
    public function addBlackListToken($token)
    {
        if (BlockToken::findOne(['token' => $token])) {
            return true;
        }
        $blockedToken = new BlockToken();
        $blockedToken->setScenario(BlockToken::SCENARIO_CREATE_BLOCK);

        $blockedToken->setAttributes([
            'user_id'    => self::getPayload($token, 'jti'),
            'expired_at' => self::getPayload($token, 'exp'),
            'token'      => $token
        ]);
        return $blockedToken->save();
    }

    /**
     * Method of validation post data
     *
     * @param $modelErrors
     * @return bool
     * @throws ExceptionDb
     */
    private function validationExceptionFirstMessage($modelErrors)
    {
        if (is_array($modelErrors) && !empty($modelErrors)) {
            $fields = array_keys($modelErrors);
            $firstMessage = current($modelErrors[$fields[0]]);
            throw new ExceptionDb($firstMessage);
        }

        return false;
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
     * Returns User model by phone number
     * @param string $phoneNumber
     * @return mixed
     */
    public function getUnverifiedUserByPhoneNumber($phoneNumber)
    {
        return static::find()->where(['phone_number' => $phoneNumber, 'status' => self::STATUS_UNVERIFIED])->one();
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
     * Process auth_key, password_reset_token, status, password
     */
    public function defaultBeforeSavePropertiesPreProcess()
    {
        $scenarios = [
            self::SCENARIO_REGISTER, self::SCENARIO_SOCIAL_REGISTER, self::SCENARIO_RECOVERY_PWD,
            self::SCENARIO_UPDATE_PASSWORD, self::SCENARIO_REGISTER_BY_BID
        ];

        $registrationScenarios = [
            self::SCENARIO_REGISTER, self::SCENARIO_REGISTER_BY_BID
        ];

        if (in_array($this->scenario, $scenarios)) {
            $this->auth_key = \Yii::$app->security->generateRandomString();
            $this->password_reset_token = \Yii::$app->security->generateRandomString() . '_' . time();
            $this->password = \Yii::$app->security->generatePasswordHash($this->password);
            $this->status = self::STATUS_VERIFIED;
        }

        if (in_array($this->scenario, $registrationScenarios)) {
            $this->status = self::STATUS_UNVERIFIED;
        }
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

        $user->email = $params['email'];
        $user->email_verification_code = null;

        if ($user->save(false)) {
            return true;
        }
        throw new ServerErrorHttpException('Server error, please try later');
    }

    /**
     * Validates passed verification code
     * @param $verificationCode string Code that user has passed
     * @param $userModel RestUserEntity
     * @return bool
     */
    public function isNewEmailVerificationValid($verificationCode, RestUserEntity $userModel):bool
    {
        if (($userModel->created_email_verification_code + \Yii::$app->params['emailVerificationCodeLifeTime']) > time()) {
            if (intval($verificationCode) === $userModel->email_verification_code) {
                return true;
            }
        }
        return false;
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
     * @param $userModel RestUserEntity
     * @return bool
     */
    public function isNewPhoneVerificationCodeValid($verificationCode, RestUserEntity $userModel):bool
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

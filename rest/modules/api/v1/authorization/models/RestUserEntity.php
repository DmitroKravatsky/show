<?php

namespace rest\modules\api\v1\authorization\models;

use rest\behaviors\ResponseBehavior;
use rest\behaviors\ValidationExceptionFirstMessage;
use rest\modules\api\v1\authorization\models\repositories\AuthorizationJwt;
use rest\modules\api\v1\authorization\models\repositories\AuthorizationRepository;
use yii\base\Exception;
use yii\behaviors\TimestampBehavior;
use common\models\user\User;
use rest\modules\api\v1\authorization\models\repositories\SocialRepository;
use Yii;
use yii\web\ErrorHandler;
use yii\web\HttpException;
use yii\web\ServerErrorHttpException;
use yii\db\Exception as ExceptionDb;

/**
 * Class RestUserEntity
 * @mixin ValidationExceptionFirstMessage
 * @mixin ResponseBehavior
 * @package rest\modules\api\v1\authorization\models
 * @property integer $id
 * @property string $password
 * @property string $confirm_password
 * @property string $password_reset_token
 * @property string $email
 * @property string $auth_key
 * @property string $source
 * @property string $source_id
 * @property string $phone_number
 * @property integer $terms_condition
 * @property integer $created_at
 * @property integer $updated_at
 * @property integer $recovery_code
 * @property integer $created_recovery_code
 */

class RestUserEntity extends User
{
    use SocialRepository, AuthorizationJwt, AuthorizationRepository;

    const ROLE_USER  = 'user';
    const ROLE_GUEST = 'guest';

    const SCENARIO_REGISTER        = 'register';
    const SCENARIO_RECOVERY_PWD    = 'recovery-password';
    const SCENARIO_UPDATE_PASSWORD = 'update-password';
    const SCENARIO_LOGIN           = 'login';

    const FB     = 'fb';
    const VK     = 'vk';
    const GMAIL  = 'gmail';
    const NATIVE = 'native';

    public $confirm_password;
    public $current_password;
    public $new_password;
    public $role;
    
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
            'id'                    => '#',
            'email'                 => 'Email',
            'phone_number'          => 'Номер телефона',
            'source'                => 'Социальная сеть',
            'source_id'             => 'Пользователь в социальной сети',
            'terms_condition'       => 'Пользовательское соглашение',
            'password'              => 'Пароль',
            'new_password'          => 'Новый пароль',
            'current_password'      => 'Текущий пароль',
            'created_at'            => 'Дата создания',
            'updated_at'            => 'Дата изменения',
            'created_recovery_code' => 'Дата создания кода востановления',
            'recovery_code'         => 'Код востановления',
        ];
    }

    /**
     * @return array
     */
    public function scenarios(): array
    {
        $scenarios = parent::scenarios();

        $scenarios[self::SCENARIO_REGISTER] = [
            'email', 'password', 'phone_number', 'terms_condition', 'source', 'source_id', 'confirm_password', 'role'
        ];

        $scenarios[self::SCENARIO_RECOVERY_PWD] = [
            'email', 'password', 'confirm_password', 'phone_number','recovery_code'
        ];

        $scenarios[self::SCENARIO_LOGIN] = ['email', 'password', 'phone_number',];

        $scenarios[self::SCENARIO_UPDATE_PASSWORD] = ['current_password', 'password', 'confirm_password', 'new_password'];

        return $scenarios;
    }

    /**
     * @return array
     */
    public function behaviors(): array
    {
        $behaviors = parent::behaviors();

        $behaviors['timestampBehavior'] = TimestampBehavior::className();
        $behaviors['responseBehavior'] = ResponseBehavior::className();
        $behaviors['validationExceptionFirstMessage'] = ValidationExceptionFirstMessage::className();

        return $behaviors;
    }

    /**
     * @return array
     */
    public function rules(): array 
    {
        return [
            ['email', 'email'],
            ['role', 'in', 'range' => [self::ROLE_GUEST, self::ROLE_USER]],
            [['email', 'phone_number'], 'unique', 'on' => self::SCENARIO_REGISTER],
            [
                'email',
                'required',
                'when' => function (User $model) {
                    return empty($model->phone_number);
                },
                'message' => 'Необходимо заполнить «Email» или «Номер телефона».',
                'on' => [self::SCENARIO_REGISTER, self::SCENARIO_LOGIN,self::SCENARIO_RECOVERY_PWD]
            ],
            [
                'phone_number',
                'required',
                'when' => function (User $model) {
                    return empty($model->email);
                },
                'message' => 'Необходимо заполнить «Email» или «Номер телефона».',
                'on' => [self::SCENARIO_REGISTER, self::SCENARIO_LOGIN,self::SCENARIO_RECOVERY_PWD]
            ],
            [
                'terms_condition',
                'required',
                'on'            => self::SCENARIO_REGISTER,
                'requiredValue' => 1,
                'message'       => Yii::t('app', 'Вы должны принять "Пользовательские соглашения."')
            ],
            ['password', 'string', 'min' => 6, 'on' => [self::SCENARIO_REGISTER,]],
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
                'on' => [self::SCENARIO_REGISTER,]
            ],
            ['password', 'required', 'on' => self::SCENARIO_LOGIN],
            [
                ['email','password', 'confirm_password','recovery_code'],
                'required',
                'on' => [self::SCENARIO_RECOVERY_PWD,]
            ],
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

        if (
            $this->scenario === self::SCENARIO_REGISTER
            || $this->scenario === self::SCENARIO_RECOVERY_PWD
            || $this->scenario === self::SCENARIO_UPDATE_PASSWORD
        ) {
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

    /**
     * Reserts recovery code after recovery password
     * @return int
     */
    public function resetRecoveryCode()
    {
        return Yii::$app->db->createCommand()
            ->update(self::tableName(), [
                'recovery_code'         => null,
                'created_recovery_code' => null
            ], ['id' => $this->getId()])->execute();
    }


    /**
     * @param bool $insert
     * @param array $changedAttributes
     */
    public function afterSave($insert, $changedAttributes)
    {
        parent::afterSave($insert, $changedAttributes);

        if ($this->scenario == self::SCENARIO_REGISTER) {
            $this->role = self::ROLE_USER;
            $userRole = Yii::$app->authManager->getRole($this->role);
            Yii::$app->authManager->assign($userRole, $this->getId());
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
        if (!Yii::$app->security->validatePassword($this->{$attribute}, $this->password)) {
            $this->addError($this->{$attribute}, Yii::t('app', 'Неверно введен старый пароль.'));
            return false;
        }

        return true;
    }

    /**
     * Check user by email and get his data
     * @param $email
     * @return null|static
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
     * Check user by phone_number and get his data
     * @param $phoneNumber
     * @return null|static
     * @throws ServerErrorHttpException
     */
    public function getUserByPhoneNumber($phoneNumber)
    {
        if (empty($restUser = RestUserEntity::findOne(['phone_number' => $phoneNumber]))) {
            throw new ServerErrorHttpException('Пользователя с таким номером телефона не существует, 
            пройдите процедуру регистрации.');
        }

        return $restUser;
    }

    /**
     * Recovery users password
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
            $this->setAttributes($postData);

            if ($this->validate() && $this->checkRecoveryCode($recoveryCode,$createdRecoveryCode,$postData['recovery_code'])){
                return $this->save();
            }
        } catch (ExceptionDb $e) {
            throw new HttpException(422, $e->getMessage());
        } catch (Exception $e) {
            Yii::error(ErrorHandler::convertExceptionToString($e));
            throw new ServerErrorHttpException('Произошла ошибка при восстановлении пароля.');
        }
        throw new ServerErrorHttpException('Произошла ошибка при восстановлении пароля.');

    }

    /**
     * Validate users recovery code
     * @param $recoveryCode
     * @param $createdRecoveryCode
     * @param $postData
     * @return bool
     */
    public function checkRecoveryCode($recoveryCode,$createdRecoveryCode,$postData)
    {
        if ($recoveryCode != $postData) {
            $this->addError('recovery_code', 'Код восстановления неверен!');
            return false;
        }elseif (!$createdRecoveryCode || $createdRecoveryCode + 3600 < time()) {
            $this->addError('created_recovery_code', 'Время кода восстановления истекло. Сгенерируйте новый!');
            return false;
        }
        return true;
    }
}
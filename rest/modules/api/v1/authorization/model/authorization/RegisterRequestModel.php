<?php

declare(strict_types=1);

namespace rest\modules\api\v1\authorization\model\authorization;

use yii\base\Model;
use rest\behaviors\ValidationExceptionFirstMessage;
use borales\extensions\phoneInput\PhoneInputValidator;
use rest\modules\api\v1\authorization\entity\AuthUserEntity;

/**
 * @property string $phone_number
 * @property string $password
 * @property $terms_condition
 * @property $confirm_password
 *
 * @mixin ValidationExceptionFirstMessage
 */
class RegisterRequestModel extends Model
{
    public $phone_number;
    public $password;
    public $terms_condition;
    public $confirm_password;

    public function rules(): array
    {
        return [
            [['phone_number', 'password', 'confirm_password'], 'trim'],
            [['phone_number', 'password', 'confirm_password', 'terms_condition',], 'required'],
            ['phone_number', 'string', 'max' => 20],
            [['phone_number'], PhoneInputValidator::class, 'region' => ['RU', 'UA', 'BY']],
            [['phone_number'], 'checkPhoneNumberExist'],
            [
                'terms_condition',
                'required',
                'requiredValue' => 1,
                'message'       => 'You must accept the User agreement.'
            ],
            [['password'], 'string', 'min' => 6],
            [['confirm_password'], 'compare', 'compareAttribute' => 'password'],
        ];
    }

    public function behaviors(): array
    {
        return [
            ValidationExceptionFirstMessage::class,
        ];
    }

    public function checkPhoneNumberExist(string $attribute): void
    {
        $condition = ['phone_number' => $this->{$attribute}, 'status' => AuthUserEntity::STATUS_VERIFIED];
        if (AuthUserEntity::find()->where($condition)->exists()) {
            $this->addError($attribute, "Phone number \"{$this->{$attribute}}\"  has already been taken.");
        }
    }
}

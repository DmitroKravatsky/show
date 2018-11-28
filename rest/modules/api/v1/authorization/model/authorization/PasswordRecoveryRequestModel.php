<?php

declare(strict_types=1);

namespace rest\modules\api\v1\authorization\model\authorization;

use yii\base\Model;
use borales\extensions\phoneInput\PhoneInputValidator;
use rest\behaviors\ValidationExceptionFirstMessage;

/**
 * @property string $phone_number
 * @property string $password
 * @property string $confirm_password
 * @property string $recovery_code
 *
 * @mixin ValidationExceptionFirstMessage
 */
class PasswordRecoveryRequestModel extends Model
{
    public $phone_number;
    public $password;
    public $confirm_password;
    public $recovery_code;

    public function rules(): array
    {
        return [
            [['phone_number', 'password', 'confirm_password', 'recovery_code'], 'trim'],
            [['phone_number', 'password', 'confirm_password', 'recovery_code'], 'required'],
            [['phone_number'], PhoneInputValidator::class, 'region' => ['RU', 'UA', 'BY']],
            [['password'], 'string', 'min' => 6],
            [['confirm_password'], 'compare', 'compareAttribute' => 'password'],
            [['recovery_code'], 'string', 'min' => 4, 'max' => 4]
        ];
    }

    public function behaviors(): array
    {
        return [
            ValidationExceptionFirstMessage::class,
        ];
    }
}

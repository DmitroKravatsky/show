<?php

declare(strict_types=1);

namespace rest\modules\api\v1\authorization\model\authorization;

use yii\base\Model;
use rest\behaviors\ValidationExceptionFirstMessage;
use borales\extensions\phoneInput\PhoneInputValidator;

/**
 * @property string $phone_number
 * @property string $verification_code
 *
 * @mixin ValidationExceptionFirstMessage
 */
class VerificationProfileRequestModel extends Model
{
    public $phone_number;
    public $verification_code;

    public function rules(): array
    {
        return [
            [['phone_number', 'verification_code'], 'trim'],
            [['phone_number', 'verification_code'], 'required'],
            [['phone_number'], 'string', 'max' => 20],
            [['phone_number'], PhoneInputValidator::class, 'region' => ['RU', 'UA', 'BY']],
            [['phone_number'], function ($attribute, $params, $validator) {
                if (!preg_match('/^[+]\d+$/', $this->$attribute)) {
                    $this->addError($attribute, 'The phone number must not contain letters');
                }
            }
            ],
            [['verification_code'], 'string', 'min' => 4, 'max' => 4]
        ];
    }

    public function behaviors(): array
    {
        return [
            ValidationExceptionFirstMessage::class,
        ];
    }
}

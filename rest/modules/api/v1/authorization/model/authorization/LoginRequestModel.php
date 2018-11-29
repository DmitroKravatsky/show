<?php

declare(strict_types=1);

namespace rest\modules\api\v1\authorization\model\authorization;

use yii\base\Model;
use rest\behaviors\ValidationExceptionFirstMessage;
use borales\extensions\phoneInput\PhoneInputValidator;

/**
 * @property string $phone_number
 * @property string $password
 *
 * @mixin ValidationExceptionFirstMessage
 */
class LoginRequestModel extends Model
{
    public $phone_number;
    public $password;

    public function rules(): array
    {
        return [
            [['phone_number', 'password'], 'trim'],
            [['phone_number', 'password'], 'required'],
            [['phone_number',], 'string', 'max' => 20],
            [['phone_number'], PhoneInputValidator::class, 'region' => ['RU', 'UA', 'BY']],
            [['phone_number'], function ($attribute, $params, $validator) {
                    if (!preg_match('/^[+]\d+$/', $this->$attribute)) {
                        $this->addError($attribute, 'The phone number must not contain letters');
                    }
                }
            ],
            [['password'], 'string', 'min' => 6],
        ];
    }

    public function behaviors(): array
    {
        return [
            ValidationExceptionFirstMessage::class,
        ];
    }
}

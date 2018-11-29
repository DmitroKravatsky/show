<?php

declare(strict_types=1);

namespace rest\modules\api\v1\authorization\model\authorization;

use yii\base\Model;
use rest\behaviors\ValidationExceptionFirstMessage;
use borales\extensions\phoneInput\PhoneInputValidator;

/**
 * @property string $phone_number
 *
 * @mixin ValidationExceptionFirstMessage
 */
class SendRecoveryCodeRequestModel extends Model
{
    public $phone_number;

    public function rules(): array
    {
        return [
            [['phone_number'], 'trim'],
            [['phone_number'], 'required'],
            [['phone_number'], PhoneInputValidator::class, 'region' => ['RU', 'UA', 'BY']],
            [['phone_number'], function ($attribute, $params, $validator) {
                if (!preg_match('/^[+]\d+$/', $this->$attribute)) {
                    $this->addError($attribute, 'The phone number must not contain letters');
                }
            }
            ],
        ];
    }

    public function behaviors(): array
    {
        return [
            ValidationExceptionFirstMessage::class,
        ];
    }
}

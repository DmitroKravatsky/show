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
        ];
    }

    public function behaviors(): array
    {
        return [
            ValidationExceptionFirstMessage::class,
        ];
    }
}

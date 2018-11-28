<?php

declare(strict_types=1);

namespace rest\modules\api\v1\authorization\model\authorization;

use yii\base\Model;
use rest\behaviors\ValidationExceptionFirstMessage;

/**
 * @property string $refresh_token
 * @mixin ValidationExceptionFirstMessage
 */
class GenerateNewAccessTokenRequestModel extends Model
{
    public $refresh_token;

    public function rules(): array
    {
        return [
            [['refresh_token'], 'trim'],
            [['refresh_token'], 'required'],
        ];
    }

    public function behaviors(): array
    {
        return [
            ValidationExceptionFirstMessage::class,
        ];
    }
}

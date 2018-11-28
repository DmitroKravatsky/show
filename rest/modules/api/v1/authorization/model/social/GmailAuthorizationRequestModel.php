<?php

declare(strict_types=1);

namespace rest\modules\api\v1\authorization\model\social;

use yii\base\Model;
use rest\behaviors\ValidationExceptionFirstMessage;

/**
 * @property string $access_token
 * @property string $terms_condition
 *
 * @mixin ValidationExceptionFirstMessage
 */
class GmailAuthorizationRequestModel extends Model
{
    public $access_token;
    public $terms_condition;

    public function rules(): array
    {
        return [
            [['access_token', 'terms_condition'], 'trim'],
            [['access_token', 'terms_condition'], 'required'],
            [
                'terms_condition',
                'required',
                'requiredValue' => 1,
                'message'       => 'You must accept the User agreement.'
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

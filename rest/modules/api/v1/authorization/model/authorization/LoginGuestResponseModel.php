<?php

declare(strict_types=1);

namespace rest\modules\api\v1\authorization\model\authorization;

/**
 * @property string $access_token
 * @property int $exp
 */
class LoginGuestResponseModel
{
    public $access_token;
    public $exp;
}

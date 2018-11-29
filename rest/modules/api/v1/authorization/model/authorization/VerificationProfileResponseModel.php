<?php

declare(strict_types=1);

namespace rest\modules\api\v1\authorization\model\authorization;

/**
 * @property int $id
 * @property string $access_token
 * @property string $refresh_token
 * @property int $exp
 */
class VerificationProfileResponseModel
{
    public $id;
    public $access_token;
    public $refresh_token;
    public $exp;
}

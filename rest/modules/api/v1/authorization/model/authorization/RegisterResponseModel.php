<?php

declare(strict_types=1);

namespace rest\modules\api\v1\authorization\model\authorization;

/**
 * @property int $id
 * @property string $phone_number
 * @property string $status
 */
class RegisterResponseModel
{
    public $id;
    public $phone_number;
    public $status;
}

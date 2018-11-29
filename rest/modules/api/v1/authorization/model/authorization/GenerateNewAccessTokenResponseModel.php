<?php

declare(strict_types=1);

namespace rest\modules\api\v1\authorization\model\authorization;

use rest\modules\api\v1\authorization\entity\AuthUserEntity;

/**
 * @property string $access_token
 * @property string $refresh_token
 * @property int $exp
 * @property AuthUserEntity $user
 */
class GenerateNewAccessTokenResponseModel
{
    public $user_id;
    public $access_token;
    public $refresh_token;
    public $exp;
    public $user;

    public function __construct()
    {
        $this->user = new AuthUserEntity();
    }
}

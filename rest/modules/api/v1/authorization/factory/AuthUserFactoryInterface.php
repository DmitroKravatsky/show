<?php

declare(strict_types=1);

namespace rest\modules\api\v1\authorization\factory;

use rest\modules\api\v1\authorization\entity\AuthUserEntity;

interface AuthUserFactoryInterface
{
    public function createGenerateNewAccessTokenResult(AuthUserEntity $entity): array;

    public function createLoginResult(AuthUserEntity $entity): array;

    public function createLoginGuestResult(AuthUserEntity $entity): array;

    public function createRegisterResult(AuthUserEntity $entity): array;

    public function createVerifyUserResult(AuthUserEntity $entity): array;

    public function createSocialAuthorizationResult(AuthUserEntity $entity): array;
}

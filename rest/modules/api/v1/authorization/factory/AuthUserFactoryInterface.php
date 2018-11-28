<?php

declare(strict_types=1);

namespace rest\modules\api\v1\authorization\factory;

use rest\modules\api\v1\authorization\entity\AuthUserEntity;
use rest\modules\api\v1\authorization\model\authorization\{
    GenerateNewAccessTokenResponseModel, LoginGuestResponseModel, LoginResponseModel,
    RegisterResponseModel, VerificationProfileResponseModel
};
use rest\modules\api\v1\authorization\model\social\SocialAuthorizationResponseModel;

interface AuthUserFactoryInterface
{
    public function createGenerateNewAccessTokenResult(AuthUserEntity $entity): GenerateNewAccessTokenResponseModel;

    public function createLoginResult(AuthUserEntity $entity): LoginResponseModel;

    public function createLoginGuestResult(AuthUserEntity $entity): LoginGuestResponseModel;

    public function createRegisterResult(AuthUserEntity $entity): RegisterResponseModel;

    public function createVerifyUserResult(AuthUserEntity $entity): VerificationProfileResponseModel;

    public function createSocialAuthorizationResult(AuthUserEntity $entity): SocialAuthorizationResponseModel;
}

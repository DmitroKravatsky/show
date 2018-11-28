<?php

declare(strict_types=1);

namespace rest\modules\api\v1\authorization\service\authorization;

use rest\modules\api\v1\authorization\model\authorization\{
    GenerateNewAccessTokenRequestModel, LoginRequestModel, RegisterRequestModel,
    SendRecoveryCodeRequestModel, VerificationProfileRequestModel, PasswordRecoveryRequestModel,
    ResendVerificationCodeRequestModel
};

interface AuthUserServiceInterface
{
    public function generateNewAccessToken(GenerateNewAccessTokenRequestModel $model): array;

    public function login(LoginRequestModel $model): array;

    public function loginGuest(): array;

    public function register(RegisterRequestModel $model): array;

    public function sendPasswordRecoveryCode(SendRecoveryCodeRequestModel $model): void;

    public function verifyUser(VerificationProfileRequestModel $model): array;

    public function passwordRecovery(PasswordRecoveryRequestModel $model): void;

    public function resendVerificationCode(ResendVerificationCodeRequestModel $model): void;

    public function logout(): void;
}

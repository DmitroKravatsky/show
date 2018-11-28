<?php

declare(strict_types=1);

namespace rest\modules\api\v1\authorization\service\authorization;

use rest\modules\api\v1\authorization\model\authorization\{
    GenerateNewAccessTokenRequestModel, GenerateNewAccessTokenResponseModel, LoginGuestResponseModel,
    LoginRequestModel, LoginResponseModel, RegisterRequestModel, RegisterResponseModel,
    SendRecoveryCodeRequestModel, VerificationProfileRequestModel, PasswordRecoveryRequestModel,
    ResendVerificationCodeRequestModel, VerificationProfileResponseModel
};

interface AuthUserServiceInterface
{
    public function generateNewAccessToken(GenerateNewAccessTokenRequestModel $model): GenerateNewAccessTokenResponseModel;

    public function login(LoginRequestModel $model): LoginResponseModel;

    public function loginGuest(): LoginGuestResponseModel;

    public function register(RegisterRequestModel $model): RegisterResponseModel;

    public function sendPasswordRecoveryCode(SendRecoveryCodeRequestModel $model): void;

    public function verifyUser(VerificationProfileRequestModel $model): VerificationProfileResponseModel;

    public function passwordRecovery(PasswordRecoveryRequestModel $model): void;

    public function resendVerificationCode(ResendVerificationCodeRequestModel $model): void;

    public function logout(): void;
}

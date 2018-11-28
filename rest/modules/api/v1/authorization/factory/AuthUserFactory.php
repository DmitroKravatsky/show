<?php

declare(strict_types=1);

namespace rest\modules\api\v1\authorization\factory;

use rest\modules\api\v1\authorization\entity\AuthUserEntity;

class AuthUserFactory implements AuthUserFactoryInterface
{
    public function createGenerateNewAccessTokenResult(AuthUserEntity $entity): array
    {
        return [
            'access_token' => $newAccessToken = $entity->getJWT(['user_id' => $entity->id]),
            'refresh_token' => $entity->refresh_token,
            'exp' => $entity::getPayload($newAccessToken, 'exp'),
            'user' => [
                'id' => $entity->getId(),
                'phone_number' => $entity->phone_number,
                'role' => $entity->getUserRole($entity->id),
                'created_at' => $entity->created_at,
                'status' => $entity->status
            ]
        ];
    }

    public function createLoginResult(AuthUserEntity $entity): array
    {
        return [
            'user_id' => $entity->id,
            'access_token' => $accessToken = $entity->getJWT(['user_id' => $entity->id]),
            'exp' => AuthUserEntity::getPayload($accessToken, 'exp'),
            'refresh_token' => $entity->refresh_token
        ];
    }

    public function createLoginGuestResult(AuthUserEntity $entity): array
    {
        return [
            'access_token' => $accessToken = $entity->getJWT(['user_id' => $entity->id]),
            'exp' => AuthUserEntity::getPayload($accessToken, 'exp'),
        ];
    }

    public function createRegisterResult(AuthUserEntity $entity): array
    {
        return [
            'id' => $entity->id,
            'phone_number' => $entity->phone_number,
            'status' => $entity->status,
        ];
    }

    public function createVerifyUserResult(AuthUserEntity $entity): array
    {
        return [
            'id' => $entity->id,
            'access_token' => $token = $entity->getJWT(['user_id' => $entity->id]),
            'exp' => AuthUserEntity::getPayload($token, 'exp'),
            'refresh_token' => $entity->refresh_token,
        ];
    }

    public function createSocialAuthorizationResult(AuthUserEntity $entity): array
    {
        return [
            'user_id' => $entity->id,
            'access_token' => $accessToken = $entity->getJWT(['user_id' => $entity->id]),
            'exp' => AuthUserEntity::getPayload($accessToken, 'exp'),
            'refresh_token' => $entity->refresh_token
        ];
    }
}

<?php

declare(strict_types=1);

namespace rest\modules\api\v1\authorization\factory;

use rest\modules\api\v1\authorization\entity\AuthUserEntity;
use rest\modules\api\v1\authorization\model\authorization\GenerateNewAccessTokenResponseModel;
use rest\modules\api\v1\authorization\model\authorization\LoginGuestResponseModel;
use rest\modules\api\v1\authorization\model\authorization\LoginResponseModel;
use rest\modules\api\v1\authorization\model\authorization\RegisterResponseModel;
use rest\modules\api\v1\authorization\model\authorization\VerificationProfileResponseModel;
use rest\modules\api\v1\authorization\model\social\SocialAuthorizationResponseModel;

class AuthUserFactory implements AuthUserFactoryInterface
{
    public function createGenerateNewAccessTokenResult(AuthUserEntity $entity): GenerateNewAccessTokenResponseModel
    {
        $model = new GenerateNewAccessTokenResponseModel();
        $model->access_token = $accessToken = $entity->getJWT(['user_id' => $entity->id]);
        $model->refresh_token = $entity->refresh_token;
        $model->exp = $entity::getPayload($accessToken, 'exp');
        $model->user->id = $entity->getId();
        $model->user->phone_number = $entity->phone_number;
        $model->user->role = $entity->getUserRole($entity->id);
        $model->user->created_at = $entity->created_at;
        $model->user->status = $entity->status;

        return $model;
    }

    public function createLoginResult(AuthUserEntity $entity): LoginResponseModel
    {
        $model = new LoginResponseModel();
        $model->user_id = $entity->id;
        $model->access_token = $accessToken = $entity->getJWT(['user_id' => $entity->id]);
        $model->exp = AuthUserEntity::getPayload($accessToken, 'exp');
        $model->refresh_token = $entity->refresh_token;

        return $model;
    }

    public function createLoginGuestResult(AuthUserEntity $entity): LoginGuestResponseModel
    {
        $model = new LoginGuestResponseModel();
        $model->access_token = $accessToken = $entity->getJWT(['user_id' => $entity->id]);
        $model->exp = AuthUserEntity::getPayload($accessToken, 'exp');

        return $model;
    }

    public function createRegisterResult(AuthUserEntity $entity): RegisterResponseModel
    {
        $model = new RegisterResponseModel();
        $model->id = $entity->id;
        $model->phone_number = $entity->phone_number;
        $model->status = $entity->status;

        return $model;
    }

    public function createVerifyUserResult(AuthUserEntity $entity): VerificationProfileResponseModel
    {
        $model = new VerificationProfileResponseModel();
        $model->id = $entity->id;
        $model-> access_token = $token = $entity->getJWT(['user_id' => $entity->id]);
        $model->exp = AuthUserEntity::getPayload($token, 'exp');
        $model->refresh_token = $entity->refresh_token;

        return $model;
    }

    public function createSocialAuthorizationResult(AuthUserEntity $entity): SocialAuthorizationResponseModel
    {
        $model = new SocialAuthorizationResponseModel();
        $model->user_id = $entity->id;
        $model->access_token = $accessToken = $entity->getJWT(['user_id' => $entity->id]);
        $model->exp = AuthUserEntity::getPayload($accessToken, 'exp');
        $model->refresh_token = $entity->refresh_token;

        return $model;
    }
}

<?php

declare(strict_types=1);

namespace rest\modules\api\v1\authorization\repository;

use rest\modules\api\v1\authorization\entity\AuthUserEntity;

interface AuthUserRepositoryInterface
{
    public function findOneById(int $id): ?AuthUserEntity;

    public function findOneByPhoneNumber(string $phoneNumber): ?AuthUserEntity;

    public function findOneByRefreshToken(string $refreshToken): ?AuthUserEntity;

    public function findOneByEmail(string $email): ?AuthUserEntity;

    public function findOneByPhoneNumberAndStatus(string $phoneNumber, string $status): ?AuthUserEntity;

    public function findOneBySourceIdOrEmail(string $sourceId, string $email);

    public function add(AuthUserEntity $entity): AuthUserEntity;

    public function update(AuthUserEntity $entity): AuthUserEntity;
}

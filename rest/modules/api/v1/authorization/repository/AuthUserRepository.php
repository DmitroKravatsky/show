<?php

declare(strict_types=1);

namespace rest\modules\api\v1\authorization\repository;

use common\models\userSocial\UserSocial;
use rest\modules\api\v1\authorization\entity\AuthUserEntity;

class AuthUserRepository implements AuthUserRepositoryInterface
{
    private $entity;

    public function __construct(AuthUserEntity $entity)
    {
        $this->entity = $entity;
    }

    public function findOneById(int $id): ?AuthUserEntity
    {
        return $this->entity::findOne($id);
    }

    public function findOneByPhoneNumber(string $phoneNumber): ?AuthUserEntity
    {
        return $this->entity::findOne(['phone_number' => $phoneNumber]);
    }

    public function findOneByRefreshToken(string $refreshToken): ?AuthUserEntity
    {
        return $this->entity::findOne(['refresh_token' => $refreshToken]);
    }

    public function findOneByEmail(string $email): ?AuthUserEntity
    {
        return $this->entity::findOne(['email' => $email]);
    }

    public function findOneByPhoneNumberAndStatus(string $phoneNumber, string $status): ?AuthUserEntity
    {
        return $this->entity::findOne(['phone_number' => $phoneNumber, 'status' => $status]);
    }

    public function findOneBySourceIdOrEmail(string $sourceId, string $email)
    {
        $socialTable = UserSocial::tableName();
        $userTable = $this->entity::tableName();

        return $this->entity::find()
            ->leftJoin($socialTable, $userTable . '.id = ' . $socialTable . '.user_id')
            ->where(['source_id' => $sourceId])
            ->orWhere(['email' => $email])
            ->one();
    }

    public function update(AuthUserEntity $entity): AuthUserEntity
    {
        $entity->save(false);

        return $entity;
    }

    public function add(AuthUserEntity $entity): AuthUserEntity
    {
        $entity->save(false);

        return $entity;
    }
}

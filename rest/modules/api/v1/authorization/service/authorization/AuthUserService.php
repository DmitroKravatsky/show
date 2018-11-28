<?php

declare(strict_types=1);

namespace rest\modules\api\v1\authorization\service\authorization;

use Yii;
use rest\modules\api\v1\authorization\model\authorization\{
    PasswordRecoveryRequestModel, RegisterRequestModel, ResendVerificationCodeRequestModel,
    SendRecoveryCodeRequestModel, LoginRequestModel, GenerateNewAccessTokenRequestModel,
    VerificationProfileRequestModel
};
use rest\modules\api\v1\authorization\entity\AuthUserEntity;
use rest\modules\api\v1\authorization\factory\AuthUserFactoryInterface;
use rest\modules\api\v1\authorization\repository\AuthUserRepositoryInterface;
use yii\db\Exception;
use yii\web\{
    NotFoundHttpException, UnauthorizedHttpException, ForbiddenHttpException,
    ErrorHandler, UnprocessableEntityHttpException
};
use common\models\userProfile\UserProfileEntity;
use rest\modules\api\v1\authorization\entity\BlockToken;

class AuthUserService implements AuthUserServiceInterface
{
    private $repository;

    public function __construct(AuthUserRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function generateNewAccessToken(GenerateNewAccessTokenRequestModel $model): array
    {
        $currentRefreshToken = $model->refresh_token;
        $authUserEntity = $this->repository->findOneByRefreshToken($currentRefreshToken);
        if ($authUserEntity === null) {
            throw new NotFoundHttpException('User is not found.');
        }

        if ($authUserEntity::isRefreshTokenExpired($authUserEntity->created_refresh_token)) {
            throw new UnauthorizedHttpException('Refresh token was expired.');
        }

        if ($authUserEntity->refresh_token !== $currentRefreshToken) {
            throw new UnauthorizedHttpException('Token was already used.');
        }

        $authUserEntity->refresh_token = $authUserEntity->getRefreshToken(['user_id' => $authUserEntity->id]);
        $authUserEntity->created_refresh_token = time();

        $authUserEntity = $this->repository->update($authUserEntity);

        /** @var AuthUserFactoryInterface $factory */
        $factory = Yii::$container->get(AuthUserFactoryInterface::class);

        return $factory->createGenerateNewAccessTokenResult($authUserEntity);
    }

    public function login(LoginRequestModel $model): array
    {
        $authUserEntity = $this->findUserByPhoneNumber($model->phone_number);

        if ($authUserEntity->status === $authUserEntity::STATUS_UNVERIFIED && $authUserEntity->register_by_bid == $authUserEntity::REGISTER_BY_BID_NO) {
            throw new ForbiddenHttpException('You must pass an account verification.');
        }

        if (!$this->validatePassword($model->password, $authUserEntity->password)) {
            throw new UnauthorizedHttpException('Wrong credentials.');
        }

        $authUserEntity->created_refresh_token = time();
        $authUserEntity->refresh_token = $authUserEntity->getRefreshToken(['user_id' => $authUserEntity->id]);

        $authUserEntity = $this->repository->update($authUserEntity);

        $this->verifyUserAfterLogin($authUserEntity);

        /** @var AuthUserFactoryInterface $factory */
        $factory = Yii::$container->get(AuthUserFactoryInterface::class);

        return $factory->createLoginResult($authUserEntity);
    }

    public function loginGuest(): array
    {
        $authUserEntity = $this->repository->findOneByEmail(Yii::$app->params['guest-email']);
        if (!$authUserEntity || !$this->validatePassword(Yii::$app->params['guest-password'], $authUserEntity->password)) {
            throw new UnauthorizedHttpException('Wrong credentials.');
        }

        /** @var AuthUserFactoryInterface $factory */
        $factory = Yii::$container->get(AuthUserFactoryInterface::class);

        return $factory->createLoginGuestResult($authUserEntity);
    }

    public function register(RegisterRequestModel $model): array
    {
        $authUserEntity = $this->repository->findOneByPhoneNumber($model->phone_number);
        if ($authUserEntity === null) {
            $authUserEntity = new AuthUserEntity();
        }

        $transaction = Yii::$app->db->beginTransaction();
        try {
            $authUserEntity->source = $authUserEntity::NATIVE;
            $authUserEntity->phone_number = $model->phone_number;
            $authUserEntity->verification_code = 0000;//rand(1000, 9999);
            $authUserEntity->auth_key = Yii::$app->security->generateRandomString();
            $authUserEntity->password_reset_token = Yii::$app->security->generateRandomString() . '_' . time();
            $authUserEntity->password = Yii::$app->security->generatePasswordHash($model->password);
            $authUserEntity->status = $authUserEntity::STATUS_UNVERIFIED;

            $authUserEntity = $this->repository->add($authUserEntity);

            $profile = new UserProfileEntity();
            $profile->user_id = $authUserEntity->id;
            $profile->save(false);

            //\Yii::$app->sendSms->run('Ваш код верификации ' . $user->verification_code, $user->phone_number);

            if ($authUserEntity->isNewRecord) {
                $this->addRole($authUserEntity, $authUserEntity::ROLE_USER);
            }

            $transaction->commit();

            /** @var AuthUserFactoryInterface $factory */
            $factory = Yii::$container->get(AuthUserFactoryInterface::class);

            return $factory->createRegisterResult($authUserEntity);
        } catch (Exception $e) {
            Yii::error(ErrorHandler::convertExceptionToString($e));
            $transaction->rollBack();
            throw new Exception($e->getMessage());
        }
    }

    public function sendPasswordRecoveryCode(SendRecoveryCodeRequestModel $model): void
    {
        $authUserEntity = $this->findUserByPhoneNumber($model->phone_number);
        $authUserEntity->recovery_code = '0000'; //rand(1000, 9999);
        $authUserEntity->created_recovery_code = time();

        $this->repository->update($authUserEntity);

//        Yii::$app->sendSms->run(
//            'Your password recovery code, ' . $authUserEntity->recovery_code . ' it will be active for an hour.',
//            $authUserEntity->phone_number
//        );
    }

    public function verifyUser(VerificationProfileRequestModel $model): array
    {
        $authUserEntity = $this->findUserByPhoneNumber($model->phone_number);
        if ($authUserEntity->verification_code !== (int) $model->verification_code) {
            throw new UnprocessableEntityHttpException('Wrong verification code.');
        }

        $authUserEntity->status = $authUserEntity::STATUS_VERIFIED;
        $authUserEntity->verification_code = null;

        $this->repository->update($authUserEntity);

        /** @var AuthUserFactoryInterface $factory */
        $factory = Yii::$container->get(AuthUserFactoryInterface::class);

        return $factory->createVerifyUserResult($authUserEntity);
    }

    public function passwordRecovery(PasswordRecoveryRequestModel $model): void
    {
        $authUserEntity = $this->findUserByPhoneNumber($model->phone_number);

        if (!$this->isRecoveryCodeValid((int) $model->recovery_code, $authUserEntity)) {
            throw new UnprocessableEntityHttpException('Recovery code is expired or incorrect.');
        }

        $authUserEntity->password = Yii::$app->security->generatePasswordHash($model->password);
        $authUserEntity->recovery_code = null;
        $authUserEntity->created_recovery_code = null;

        $this->repository->update($authUserEntity);
    }

    public function resendVerificationCode(ResendVerificationCodeRequestModel $model): void
    {
        $authUserEntity = $this->findUserByPhoneNumber($model->phone_number);
        $authUserEntity->verification_code = 0000;//rand(1000, 9999);
        $this->repository->update($authUserEntity);

        //Yii::$app->sendSms->run('Your verification code: ' . $authUserEntity->verification_code, $authUserEntity->phone_number);
    }

    public function logout(): void
    {
        $authUserEntity = $this->repository->findOneById(Yii::$app->user->id);
        if ($authUserEntity === null) {
            throw new NotFoundHttpException('User is not found.');
        }

        $this->addBlackListToken($authUserEntity->getAuthKey());
    }

    private function verifyUserAfterLogin(AuthUserEntity $entity): void
    {
        if ($entity->register_by_bid === $entity::REGISTER_BY_BID_YES && $entity->status !== $entity::STATUS_VERIFIED) {
            $entity->status = $entity::STATUS_VERIFIED;
            $this->repository->update($entity);
        }
    }

    private function validatePassword(string $password, string $hash): bool
    {
        return Yii::$app->security->validatePassword($password, $hash);
    }

    private function addRole(AuthUserEntity $entity, string $role): void
    {
        $userRole = Yii::$app->authManager->getRole($role);
        Yii::$app->authManager->assign($userRole, $entity->getId());
    }

    private function findUserByPhoneNumber(string $phoneNumber): AuthUserEntity
    {
        $authUserEntity = $this->repository->findOneByPhoneNumber($phoneNumber);
        if ($authUserEntity === null) {
            throw new NotFoundHttpException('User is not found.');
        }

        return $authUserEntity;
    }

    private function isRecoveryCodeValid(int $recoveryCode, AuthUserEntity $authUserEntity): bool
    {
        $isTimeExpired = ($authUserEntity->created_recovery_code + Yii::$app->params['recoveryCodeDurationInSeconds']) < time();
        if (!$isTimeExpired && $recoveryCode === (int) $authUserEntity->recovery_code) {
            return true;
        }

        return false;
    }

    private function addBlackListToken(string $token): bool
    {
        if (BlockToken::findOne(['token' => $token])) {
            return true;
        }

        $blockedToken = new BlockToken();
        $blockedToken->setScenario(BlockToken::SCENARIO_CREATE_BLOCK);

        $blockedToken->setAttributes([
            'user_id'    => AuthUserEntity::getPayload($token, 'jti'),
            'expired_at' => AuthUserEntity::getPayload($token, 'exp'),
            'token'      => $token
        ]);

        return $blockedToken->save();
    }
}

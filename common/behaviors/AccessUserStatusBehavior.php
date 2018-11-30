<?php
namespace common\behaviors;

use Yii;
use rest\modules\api\v1\authorization\repository\AuthUserRepositoryInterface;
use rest\modules\api\v1\authorization\entity\AuthUserEntity;
use yii\{
    base\Behavior, web\HttpException
};

class AccessUserStatusBehavior extends Behavior
{
    public $message;

    public function checkUserRole()
    {
        /** @var AuthUserRepositoryInterface $userRepository */
        $userRepository = Yii::$container->get(AuthUserRepositoryInterface::class);
        $user = $userRepository->findOneById(Yii::$app->user->id);
        $statuses = [AuthUserEntity::STATUS_UNVERIFIED, AuthUserEntity::STATUS_BANNED, AuthUserEntity::STATUS_DELETED];

        if (!$user || in_array($user->status, $statuses)) {
            throw new HttpException(403, $this->message);
        }
    }
}

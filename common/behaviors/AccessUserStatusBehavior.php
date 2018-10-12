<?php
namespace common\behaviors;

use rest\modules\api\v1\authorization\models\RestUserEntity;
use Yii;
use yii\{
    base\Behavior, web\HttpException
};

/**
 * Class AccessUserStatusBehavior
 *
 * @package common\behaviors
 */
class AccessUserStatusBehavior extends Behavior
{
    /**
     * @var array
     */
    public $message;

    /**
     * @throws HttpException
     */
    public function checkUserRole()
    {
        $user = RestUserEntity::findOne(['id' => Yii::$app->user->identity->getId()]);
        $statuses = [RestUserEntity::STATUS_UNVERIFIED, RestUserEntity::STATUS_BANNED, RestUserEntity::STATUS_DELETED];

        if (!$user || in_array($user->status, $statuses)) {
            throw new HttpException(403, $this->message);
        }
    }
}

<?php

namespace common\models\userProfile\repositories;

use common\models\userProfile\UserProfileEntity;
use Yii;
use yii\web\NotFoundHttpException;

/**
 * Class RestUserProfileRepository
 * @package common\models\userProfile\repositories
 */
trait RestUserProfileRepository
{
    /**
     * @param $id
     * @return array
     * @throws NotFoundHttpException
     */
    public function getProfile($id): array
    {
        if (empty($userProfile = self::findOne(['id' => $id, 'user_id' => Yii::$app->user->id]))) {
            throw new NotFoundHttpException('Профиль не найден.');
        }

        /** @var UserProfileEntity $userProfile */
        return $userProfile->getAttributes(['name', 'last_name', 'email', 'phone_number', 'avatar']);
    }
}
<?php

namespace common\models\userProfile\repositories;

use common\models\userProfile\UserProfileEntity;
use rest\modules\api\v1\authorization\models\RestUserEntity;
use Yii;
use yii\web\NotFoundHttpException;
use yii\web\ServerErrorHttpException;
use yii\web\UnprocessableEntityHttpException;

/**
 * Class RestUserProfileRepository
 * @package common\models\userProfile\repositories
 */
trait RestUserProfileRepository
{
    /**
     * Returns User attribute values
     * 
     * @return array
     * @throws NotFoundHttpException
     */
    public function getProfile(): array
    {
        return self::find()
            ->select(['name', 'last_name', 'avatar', 'email', 'phone_number'])
            ->leftJoin('user', 'user_profile.user_id = user.id')
            ->where(['user.id' => Yii::$app->user->id])
            ->asArray()
            ->one();
    }

    /**
     * @param $params
     * @return UserProfileEntity
     * @throws ServerErrorHttpException
     * @throws UnprocessableEntityHttpException
     * @throws \yii\db\Exception
     */
    public function updateProfile($params): UserProfileEntity
    {
        $transaction = Yii::$app->db->beginTransaction();
        
        try {
            $user = RestUserEntity::findOne(Yii::$app->user->id);
            $user->setAttributes($params);
            if (!$user->validate()) {
                $this->throwModelException($user->errors);
            }

            $userProfile = UserProfileEntity::findOne(['user_id' => $user->id]);
            $userProfile->setScenario(UserProfileEntity::SCENARIO_UPDATE);
            $userProfile->setAttributes($params);
            if (!$userProfile->validate()) {
                $this->throwModelException($userProfile->errors);
            }

            if ($user->save() && $userProfile->save()) {
                $transaction->commit();
                return $userProfile;
            }

            throw new ServerErrorHttpException;
        } catch (UnprocessableEntityHttpException $e) {
            $transaction->rollBack();
            throw new UnprocessableEntityHttpException($e->getMessage());
        } catch (\Exception $e) {
            $transaction->rollBack();
            throw new ServerErrorHttpException(Yii::t('app', 'Произошла ошибка при изменении профиля.'));
        }
    }

    public static function getFullName($userId)
    {
        if (!empty($userProfile = self::findOne($userId))) {
            return $userProfile->name . ' ' . $userProfile->last_name;
        }

        throw new NotFoundHttpException('Пользователь не найден.');
    }
}
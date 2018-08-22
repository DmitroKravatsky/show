<?php

namespace common\models\userProfile\repositories;

use common\models\userProfile\UserProfileEntity;
use rest\modules\api\v1\authorization\models\RestUserEntity;
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
     *
     * @throws NotFoundHttpException if there is no such user
     * @throws ServerErrorHttpException
     */
    public function getProfile(): array
    {
        try {
            $userProfile =  self::find()
                ->select(['user_profile.user_id', 'user_profile.name', 'user_profile.last_name', 'user_profile.avatar',
                    'user.email', 'user.phone_number', 'user.source'])
                ->joinWith('user', false)
                ->where(['user_profile.user_id' => \Yii::$app->user->id])
                ->asArray()
                ->one();

            if (!$userProfile) {
                throw new NotFoundHttpException();
            }

            return $this->getSocialService($userProfile);

        } catch (NotFoundHttpException $e) {
            throw new NotFoundHttpException('User profile is not found');
        } catch (ServerErrorHttpException $e) {
            throw new ServerErrorHttpException('Internal server error');
        }
    }

    /**
     * Updates a user profile
     *
     * @param $params array of the POST data
     *
     * @return UserProfileEntity
     *
     * @throws ServerErrorHttpException
     * @throws UnprocessableEntityHttpException
     * @throws \yii\db\Exception
     */
    public function updateProfile(array $params): UserProfileEntity
    {
        $transaction = \Yii::$app->db->beginTransaction();
        try {
            $userProfile = UserProfileEntity::findOne(['user_id' => \Yii::$app->user->id]);
            $userProfile->setScenario(UserProfileEntity::SCENARIO_UPDATE);
            if (isset($params['base64_image'])) {
                $params['avatar'] = $this->updateAvatar($params);
                unset($params['base64_image']);
            }
            $userProfile->setAttributes($params);
            if (!$userProfile->validate()) {
                $this->throwModelException($userProfile->errors);
            }

            if ($userProfile->save()) {
                $transaction->commit();
                return $userProfile;
            }

            throw new ServerErrorHttpException;
        } catch (UnprocessableEntityHttpException $e) {
            $transaction->rollBack();
            throw new UnprocessableEntityHttpException($e->getMessage());
        } catch (\Exception $e) {
            $transaction->rollBack();
            throw new ServerErrorHttpException(\Yii::t('app', 'Произошла ошибка при изменении профиля.'));
        }
    }

    /**
     * Generates a user full name
     *
     * @param $userId int
     *
     * @return string
     *
     * @throws NotFoundHttpException if there is no such user
     */
    public static function getFullName(int $userId)
    {
        if (!empty($userProfile = self::findOne(['user_id' => $userId]))) {
            return $userProfile->name . ' ' . $userProfile->last_name;
        }

        throw new NotFoundHttpException('Пользователь не найден.');
    }

    /**
     * Adds a field that marks a social network binded with a user
     *
     * @param array $userModel
     * @return array|bool
     */
    public function getSocialService(array $userModel)
    {
        if (!$userModel['source'] || $userModel['source'] === 'native') {
            return $userModel;
        }

        if ($userModel['source'] === RestUserEntity::FB) {
            $userModel['is_fb_auth'] = true;
            $userModel['is_gmail_auth'] = false;
            unset($userModel['source']);
            return $userModel;
        }

        if ($userModel['source'] === RestUserEntity::GMAIL) {
            $userModel['is_gmail_auth'] = true;
            $userModel['is_fb_auth'] = false;
            unset($userModel['source']);
            return $userModel;
        }

        return $userModel;
    }

    /**
     * Updates user avatar
     * @param array $params
     * @return null|static
     * @throws ServerErrorHttpException
     */
    public function updateAvatar(array $params)
    {
        /** @var \frostealth\yii2\aws\s3\Service $s3 */
        $s3 = \Yii::$app->get('s3');

        $fileName = \Yii::$app->params['s3_folders']['user_profile'] . '/user-' . \Yii::$app->user->id
            . '/' . \Yii::$app->security->generateRandomString() . '.' . \Yii::$app->params['picture_format'];

        $result = $s3->commands()->put($fileName, base64_decode($params['base64_image']))
            ->withContentType("image/jpeg")->execute();
        return $result->get('ObjectURL');

    }
}

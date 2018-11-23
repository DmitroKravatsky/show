<?php

namespace common\models\userProfile\repositories;

use Yii;
use yii\web\NotFoundHttpException;
use yii\web\ServerErrorHttpException;
use yii\web\UnprocessableEntityHttpException;
use common\models\userProfile\UserProfileEntity;
use common\models\userSocial\UserSocial;

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
                ->with('userSocials')
                ->where(['user_profile.user_id' => \Yii::$app->user->id])
                ->asArray()
                ->one();

            if (!$userProfile) {
                throw new NotFoundHttpException();
            }

            $userProfile['is_gmail_auth'] = false;
            $userProfile['is_fb_auth'] = false;

            if (!empty($userProfile['userSocials'])) {
                return $this->getSocialService($userProfile);
            }
            unset($userProfile['userSocials'], $userProfile['source']);

            return $userProfile;

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
        $transaction = Yii::$app->db->beginTransaction();
        try {
            $userProfile = static::findUserProfile(Yii::$app->user->id);

            $userProfile->setScenario(UserProfileEntity::SCENARIO_UPDATE);
            $userProfile->setAttributes($params);
            if (!$userProfile->validate()) {
                $this->throwModelException($userProfile->errors);
            }

            if (isset($params['avatar_base64'])) {
                $userProfile->avatar = $this->updateAvatar($params['avatar_base64']);
            }

            if ($userProfile->save(false)) {
                $transaction->commit();
                return $userProfile;
            }

            throw new ServerErrorHttpException;
        } catch (UnprocessableEntityHttpException $e) {
            $transaction->rollBack();
            throw new UnprocessableEntityHttpException($e->getMessage());
        } catch (\Exception $e) {
            $transaction->rollBack();
            throw new ServerErrorHttpException(\Yii::t('app', 'Server error occurred while updating profile'));
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
     * @return array
     */
    public function getSocialService(array $userModel)
    {
        foreach ($userModel['userSocials'] as $item) {
            if ($item['source_name'] == UserSocial::SOURCE_FB) {
                $userModel['is_fb_auth'] = true;
            }
            if ($item['source_name'] == UserSocial::SOURCE_GMAIL) {
                $userModel['is_gmail_auth'] = true;
            }
        }
        unset($userModel['userSocials'], $userModel['source']);

        return $userModel;
    }

    public function updateAvatar(string $base64)
    {
        /** @var \frostealth\yii2\aws\s3\Service $s3 */
        $s3 = Yii::$app->get('s3');

        $fileName = Yii::$app->params['s3_folders']['user_profile']
            . '/user-'
            . Yii::$app->user->id
            . '/'
            . time()
            . '.'
            . Yii::$app->params['picture_format'];

        $result = $s3->commands()->put($fileName, base64_decode($base64))->withContentType("image/jpeg")->execute();

        return $result->get('ObjectURL');
    }

    public static function findUserProfile($userId): UserProfileEntity
    {
        $userProfile = static::find()->where(['user_id' => $userId])->one();
        if (!$userProfile) {
            throw new NotFoundHttpException();
        }
        return $userProfile;
    }
}

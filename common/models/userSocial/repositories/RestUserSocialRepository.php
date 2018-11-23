<?php

namespace common\models\userSocial\repositories;

use common\models\user\User;
use common\models\userProfile\UserProfileEntity;
use common\models\userSocial\UserSocial;
use GuzzleHttp\Client;
use yii\web\BadRequestHttpException;
use yii\web\NotFoundHttpException;
use yii\web\ServerErrorHttpException;
use rest\modules\api\v1\authorization\models\RestUserEntity;
use Yii;
use yii\web\UnprocessableEntityHttpException;

trait RestUserSocialRepository
{
    /**
     * @param string $token
     * @return array
     * @throws UnprocessableEntityHttpException
     * @throws BadRequestHttpException
     * @throws ServerErrorHttpException
     */
    public function bindGmail($token)
    {
        $transaction = \Yii::$app->db->beginTransaction();
        try {
            $client = new Client(['headers' => ['Content-Type' => 'application/json']]);
            $result = $client->request(
                'GET',
                'https://www.googleapis.com/oauth2/v1/userinfo',
                [
                    'query' => [
                        'access_token' => $token,
                    ]
                ]
            );

            if ($result->getStatusCode() === 200) {
                $userData = json_decode($result->getBody()->getContents());
                if (isset($userData->error)) {
                    throw new ServerErrorHttpException;
                }
                if (static::isNetworkBindToCurrentUser(self::SOURCE_GMAIL, $userData->id)) {
                    throw new BadRequestHttpException('Social network is already bind');
                }

                $userSocial = new UserSocial();
                $userSocial->setAttributes([
                    'user_id' => Yii::$app->user->id,
                    'source_name' => self::SOURCE_GMAIL,
                    'source_id' => $userData->id,
                ]);

                if (!$userSocial->save()) {
                    $this->throwModelException($userSocial->errors);
                }

                if (!static::updateUserProfileBySocialNetwork($userData->given_name, $userData->family_name, $userData->picture)) {
                    throw new ServerErrorHttpException();
                }

                $user = RestUserEntity::findOne(Yii::$app->user->id);
                if (isset($userData->email) && empty($user->email)) {
                    $user->email = $userData->email;
                }
                $user->status = RestUserEntity::STATUS_VERIFIED;
                if (!$user->save()) {
                    $this->throwModelException($user->errors);
                }

                $transaction->commit();

                return $user->profile->getProfile();
            }
            throw new ServerErrorHttpException();
        } catch (UnprocessableEntityHttpException $e) {
            throw new UnprocessableEntityHttpException($e->getMessage());
        } catch (BadRequestHttpException $e) {
            throw new BadRequestHttpException($e->getMessage());
        } catch (\Exception $e) {
            \Yii::error($e->getMessage());
            $transaction->rollBack();
            throw new ServerErrorHttpException('Social network was successfully bind');
        }
    }

    /**
     * @param string $token
     * @return array
     * @throws UnprocessableEntityHttpException
     * @throws BadRequestHttpException
     * @throws ServerErrorHttpException
     */
    public function bindFb($token)
    {
        $transaction = \Yii::$app->db->beginTransaction();
        try {
            $client = new Client(['headers' => ['Content-Type' => 'application/json']]);
            $result = $client->request(
                'GET',
                'https://graph.facebook.com/me',
                [
                    'query' => [
                        'access_token' => $token,
                        'fields' => 'id, first_name, last_name, picture.type(large), email',
                        'v' => '2.12'
                    ]
                ]
            );

            if ($result->getStatusCode() === 200) {
                $userData = json_decode($result->getBody()->getContents());
                if (isset($userData->error)) {
                    throw new ServerErrorHttpException;
                }
                if (static::isNetworkBindToCurrentUser(self::SOURCE_FB, $userData->id)) {
                    throw new BadRequestHttpException('Social network is already bind');
                }

                $userSocial = new UserSocial();
                $userSocial->setAttributes([
                    'user_id' => Yii::$app->user->id,
                    'source_name' => self::SOURCE_FB,
                    'source_id' => $userData->id,
                ]);

                if (!$userSocial->save()) {
                    $this->throwModelException($userSocial->errors);
                }

                if (!static::updateUserProfileBySocialNetwork($userData->first_name, $userData->last_name, $userData->picture->data->url)) {
                    throw new ServerErrorHttpException();
                }

                $user = RestUserEntity::findOne(Yii::$app->user->id);
                if (isset($userData->email) && empty($user->email)) {
                    $user->email = $userData->email;
                }
                $user->status = RestUserEntity::STATUS_VERIFIED;
                if (!$user->save()) {
                    $this->throwModelException($user->errors);
                }

                $transaction->commit();

                return $user->profile->getProfile();
            }
            throw new ServerErrorHttpException();
        } catch (UnprocessableEntityHttpException $e) {
                throw new UnprocessableEntityHttpException($e->getMessage());
        } catch (BadRequestHttpException $e) {
            throw new BadRequestHttpException($e->getMessage());
        } catch (\Exception $e) {
            \Yii::error($e->getMessage());
            $transaction->rollBack();
            throw new ServerErrorHttpException('Something is wrong, please try again later');
        }
    }

    /**
     * @param string $sourceName
     * @return array
     * @throws NotFoundHttpException
     * @throws ServerErrorHttpException
     */
    public function unbindSocialNetwork($sourceName)
    {
       $userSocial = UserSocial::find()->where(['user_id' => Yii::$app->user->id, 'source_name' => $sourceName])->one();
       if (empty($userSocial)) {
           throw new NotFoundHttpException("Social network wasn't found");
       }
       try {
           $userSocial->delete();
           $user = RestUserEntity::findOne(Yii::$app->user->id);
           if ($user->source == RestUserEntity::SOCIAL && !static::isExistBindNetworksToCurrentUser()) {
               $user->status = RestUserEntity::STATUS_DELETED;
               $user->save(false, ['status']);
           }
           return array_merge($user->profile->getProfile(), ['is_deleted' => User::isUserDeleted($user->id)]);
       } catch (\Exception $e) {
           throw new ServerErrorHttpException('Something is wrong, please try again later');
       }
    }

    /**
     * @param null $firstName
     * @param null $lastName
     * @param null $avatar
     * @return bool
     */
    public static function updateUserProfileBySocialNetwork($firstName = null, $lastName = null, $avatar = null)
    {
        $userProfile = UserProfileEntity::findOne(['user_id' => Yii::$app->user->id]);
        if (empty($userProfile->name)) {
            $userProfile->name = $firstName;
        }
        if (empty($userProfile->last_name)) {
            $userProfile->last_name = $lastName;
        }
        if (empty($userProfile->avatar)) {
            $userProfile->avatar = $avatar;
        }
        return $userProfile->save();
    }

    /**
     * @param string $source
     * @param string $sourceId
     * @return bool
     */
    public static function isNetworkBindToCurrentUser($source, $sourceId)
    {
        return !empty(UserSocial::find()->where(['user_id' => Yii::$app->user->id, 'source_name' => $source, 'source_id' => $sourceId])->one());
    }

    /**
     * @return bool
     */
    public static function isExistBindNetworksToCurrentUser()
    {
        return !empty(UserSocial::find()->where(['user_id' => Yii::$app->user->id])->all());
    }
}


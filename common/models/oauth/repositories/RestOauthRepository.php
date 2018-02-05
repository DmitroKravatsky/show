<?php

namespace common\models\oauth\repositories;

use common\models\oauth\OauthEntity;
use common\models\User;
use common\models\userProfile\UserProfileEntity;
use GuzzleHttp\Client;
use rest\behaviors\ResponseBehavior;
use rest\behaviors\ValidationExceptionFirstMessage;
use yii\web\ServerErrorHttpException;
use Yii;
use yii\web\UnprocessableEntityHttpException;

/**
 * Class RestOauthRepository
 * @package common\models\oauth\repositories
 */
trait RestOauthRepository
{
    /**
     * @param $params
     * @return array|bool
     * @throws ServerErrorHttpException
     * @throws UnprocessableEntityHttpException
     */
    public function vkRegister($params)
    {
        $transaction = Yii::$app->db->beginTransaction();
        try {
            $client = new Client(['headers' => ['Content-Type' => 'application/json']]);
            $result = $client->request(
                'GET',
                'https://api.vk.com/method/users.get',
                [
                    'query' => [
                        'access_token' => $params['token'],
                        'fields' => 'photo_50',
                    ]
                ]
            );

            if ($result->getStatusCode() == 200) {
                $userData = json_decode($result->getBody()->getContents());
                $userData = array_shift($userData->response);

                $user = new User([
                    'auth_key' => Yii::$app->getSecurity()->generateRandomString(32),
                    'email' => $params['email'],
                    'password_hash' => Yii::$app->getSecurity()->generateRandomString(32)
                ]);

                if (!$user->validate()) {
                    return (new ValidationExceptionFirstMessage())->throwModelException($user->errors);
                }

                $user->save(false);

                $userProfile = new UserProfileEntity([
                    'name' => $userData->first_name,
                    'last_name' => $userData->last_name,
                    'user_id' => $user->id,
                    'avatar' => $userData->photo_50
                ]);

                $userProfile->save(false);

                $oath = new OauthEntity([
                    'user_id' => $user->id,
                    'source' => OauthEntity::VK,
                    'source_id' => (string)$userData->uid
                ]);

                if ($oath->save(false)) {
                    $transaction->commit();
                    return (new ResponseBehavior())->setResponse(201, 'Регистрация прошла успешно.');
                }
            }
        } catch (UnprocessableEntityHttpException $e) {
            throw new UnprocessableEntityHttpException($e->getMessage());
        } catch (\Exception $e) {
            $transaction->rollBack();
            throw new ServerErrorHttpException('Произошла ошибка при регистрации.');
        }
    }
}
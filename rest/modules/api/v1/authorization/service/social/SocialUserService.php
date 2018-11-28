<?php

declare(strict_types=1);

namespace rest\modules\api\v1\authorization\service\social;

use Yii;
use rest\modules\api\v1\authorization\entity\AuthUserEntity;
use rest\modules\api\v1\authorization\repository\AuthUserRepositoryInterface;
use rest\modules\api\v1\authorization\factory\AuthUserFactoryInterface;
use rest\modules\api\v1\authorization\model\social\{
    FbAuthorizationRequestModel, GmailAuthorizationRequestModel, SocialAuthorizationResponseModel
};
use common\models\userProfile\UserProfileEntity;
use common\models\userSocial\UserSocial;
use yii\web\ServerErrorHttpException;
use yii\web\ErrorHandler;
use GuzzleHttp\Client;

class SocialUserService implements SocialUserServiceInterface
{
    private $repository;

    public function __construct(AuthUserRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function fbAuthorization(FbAuthorizationRequestModel $model): SocialAuthorizationResponseModel
    {
        $transaction = Yii::$app->db->beginTransaction();
        try {
            $client = new Client(['headers' => ['Content-Type' => 'application/json']]);
            $result = $client->request(
                'GET',
                'https://graph.facebook.com/me',
                [
                    'query' => [
                        'access_token' => $model->access_token,
                        'fields'       => 'id, first_name, last_name, picture.type(large), email',
                        'v'            => '2.12'
                    ]
                ]
            );

            if (!(int) $result->getStatusCode() === 200) {
                throw new ServerErrorHttpException('Server Error');
            }

            $userData = json_decode($result->getBody()->getContents());
            if (isset($userData->id)) {
                /** @var AuthUserFactoryInterface $factory */
                $factory = Yii::$container->get(AuthUserFactoryInterface::class);

                $authUserEntity = $this->repository->findOneBySourceIdOrEmail((string) $userData->id, (string) $userData->email);
                if ($authUserEntity !== null) {
                    $authUserEntity = $this->fbLogin($authUserEntity);
                    $transaction->commit();

                    return $factory->createSocialAuthorizationResult($authUserEntity);
                }

                $authUserEntity = $this->fbRegister($userData);
                $transaction->commit();

                return $factory->createSocialAuthorizationResult($authUserEntity);
            }
            throw new ServerErrorHttpException('Server Error');
        } catch (\Exception $e) {
            Yii::error($e->getMessage());
            $transaction->rollBack();
            throw new ServerErrorHttpException($e->getMessage());
        }
    }

    public function gmailAuthorization(GmailAuthorizationRequestModel $model): SocialAuthorizationResponseModel
    {
        $transaction = \Yii::$app->db->beginTransaction();
        try {
            $client = new Client(['headers' => ['Content-Type' => 'application/json']]);
            $result = $client->request(
                'GET',
                'https://www.googleapis.com/oauth2/v1/userinfo',
                [
                    'query' => [
                        'access_token' => $model->access_token,
                    ]
                ]
            );

            if (!(int) $result->getStatusCode() === 200) {
                throw new ServerErrorHttpException('Server Error');
            }

            $userData = json_decode($result->getBody()->getContents());
            if (isset($userData->error)) {
                throw new ServerErrorHttpException;
            }

            if (isset($userData->id)) {
                /** @var AuthUserFactoryInterface $factory */
                $factory = Yii::$container->get(AuthUserFactoryInterface::class);

                $authUserEntity = $this->repository->findOneBySourceIdOrEmail((string) $userData->id, (string) $userData->email);
                if ($authUserEntity !== null) {
                    $authUserEntity = $this->gmailLogin($authUserEntity);
                    $transaction->commit();

                    return $factory->createSocialAuthorizationResult($authUserEntity);
                }

                $authUserEntity = $this->gmailRegister($userData);
                $transaction->commit();

                return $factory->createSocialAuthorizationResult($authUserEntity);
            }

            throw new ServerErrorHttpException('Server Error');
        } catch (\Exception $e) {
            \Yii::error($e->getMessage());
            $transaction->rollBack();
            throw new ServerErrorHttpException('Something is wrong, please try again later');
        }

    }

    private function gmailRegister($userData): AuthUserEntity
    {
        $transaction = Yii::$app->db->beginTransaction();
        try {
            $data = [
                'source' => AuthUserEntity::SOCIAL,
                'password' => $pass = Yii::$app->security->generateRandomString(10),
                'status' => AuthUserEntity::STATUS_VERIFIED,
                'auth_key' => Yii::$app->security->generateRandomString()
            ];

            if (isset($userData->email)) {
                $data['email'] = $userData->email;
            }
            if (isset($userData->phone_number)) {
                $data['phone_number'] = $userData->phone_number;
            }

            $authUserEntity = new AuthUserEntity();
            $authUserEntity->setAttributes($data, false);

            $authUserEntity = $this->repository->add($authUserEntity);

            $viewPath = '@common/views/mail/sendPassword-html.php';
            if (!empty($userData->email)) {
                Yii::$app->sendMail->run(
                    $viewPath,
                    ['email' => $authUserEntity->email, 'password' => $pass],
                    Yii::$app->params['supportEmail'], $authUserEntity->email, 'Your password'
                );
            }

            $userProfile = new UserProfileEntity();
            $userProfile->scenario = UserProfileEntity::SCENARIO_CREATE;
            $userProfile->setAttributes([
                'name'      => $userData->given_name,
                'last_name' => $userData->family_name,
                'user_id'   => $authUserEntity->id,
                'avatar'    => $userData->picture
            ]);

            $userProfile->save();

            $authUserEntity->refresh_token = $authUserEntity->getRefreshToken(['user_id' => $authUserEntity->id]);
            $authUserEntity->created_refresh_token = time();
            $authUserEntity->save(false, ['refresh_token', 'created_refresh_token']);

            $userSocial = new UserSocial();
            $userSocial->setAttributes([
                'user_id' => $authUserEntity->id,
                'source_id' => $userData->id,
                'source_name' => UserSocial::SOURCE_GMAIL,
            ]);
            $userSocial->save();

            $transaction->commit();

            return $authUserEntity;
        } catch (ServerErrorHttpException $e) {
            $transaction->rollBack();
            Yii::error($e->getMessage());
            throw new ServerErrorHttpException($e->getMessage());
        }

    }

    private function gmailLogin(AuthUserEntity $authUserEntity): AuthUserEntity
    {
        if (AuthUserEntity::isRefreshTokenExpired($authUserEntity->created_refresh_token)) {
            $authUserEntity->created_refresh_token = time();
            $authUserEntity->refresh_token = $authUserEntity->getRefreshToken(['user_id' => $authUserEntity->id]);

            $authUserEntity = $this->repository->update($authUserEntity);
        }

        return $authUserEntity;
    }

    private function fbLogin(AuthUserEntity $authUserEntity): AuthUserEntity
    {
        if (AuthUserEntity::isRefreshTokenExpired($authUserEntity->created_refresh_token)) {
            $authUserEntity->created_refresh_token = time();
            $authUserEntity->refresh_token = $authUserEntity->getRefreshToken(['user_id' => $authUserEntity->id]);

            $authUserEntity = $this->repository->update($authUserEntity);
        }

        return $authUserEntity;
    }

    private function fbRegister($userData): AuthUserEntity
    {
        $transaction = Yii::$app->db->beginTransaction();
        try {
            $data = [
                'source' => AuthUserEntity::SOCIAL,
                'password' => $pass = Yii::$app->security->generateRandomString(10),
                'status' => AuthUserEntity::STATUS_VERIFIED,
                'auth_key' => Yii::$app->security->generateRandomString()
            ];

            if (isset($userData->email)) {
                $data['email'] = $userData->email;
            }
            if (isset($userData->phone_number)) {
                $data['phone_number'] = $userData->phone_number;
            }
            $authUserEntity = new AuthUserEntity();
            $authUserEntity->setAttributes($data, false);

            $authUserEntity = $this->repository->add($authUserEntity);

            $viewPath = '@common/views/mail/sendPassword-html.php';
            if (!empty($userData->email)) {
                Yii::$app->sendMail->run(
                    $viewPath,
                    ['email' => $authUserEntity->email, 'password' => $pass],
                    Yii::$app->params['supportEmail'], $authUserEntity->email, 'Your password'
                );
            }

            $userProfile = new UserProfileEntity();
            $userProfile->scenario = UserProfileEntity::SCENARIO_CREATE;
            $userProfile->setAttributes([
                'name'      => $userData->first_name,
                'last_name' => $userData->last_name,
                'user_id'   => $authUserEntity->id,
                'avatar'    => $userData->picture->data->url
            ]);

            $userProfile->save();

            $authUserEntity->refresh_token = $authUserEntity->getRefreshToken(['user_id' => $authUserEntity->id]);
            $authUserEntity->created_refresh_token = time();
            $authUserEntity = $this->repository->update($authUserEntity);

            $userSocial = new UserSocial();
            $userSocial->setAttributes([
                'user_id' => $authUserEntity->id,
                'source_id' => $userData->id,
                'source_name' => UserSocial::SOURCE_FB,
            ]);
            $userSocial->save();

            $transaction->commit();

            return $authUserEntity;
        } catch (\Exception $e) {
            $transaction->rollBack();
            Yii::error(ErrorHandler::convertExceptionToString($e));
            throw new ServerErrorHttpException($e->getMessage());
        }
    }
}

<?php
/**
 * Created by PhpStorm.
 * User: dima
 * Date: 22.02.18
 * Time: 21:44
 */

namespace rest\modules\api\v1\authorization\controllers\actions\authorization;


use PHPUnit\Framework\Exception;
use rest\behaviors\ResponseBehavior;
use rest\modules\api\v1\authorization\models\RestUserEntity;
use Yii;
use yii\base\ErrorHandler;
use yii\filters\auth\HttpBearerAuth;
use yii\rest\Action;
use yii\web\HttpException;
use yii\web\NotFoundHttpException;
use yii\db\Exception as ExceptionDb;
use yii\web\ServerErrorHttpException;

/**
 * Class GenerateNewAccessTokenAction
 * @package rest\modules\api\v1\authorization\controllers\actions\authorization
 */
class GenerateNewAccessTokenAction extends Action
{
    /**
     * @return array
     */
    public function behaviors(): array
    {
        $behaviors = parent::behaviors();

        $behaviors['responseBehavior'] = ResponseBehavior::className();

        return $behaviors;
    }

    /**
     * @return mixed
     * @throws HttpException
     * @throws NotFoundHttpException
     * @throws ServerErrorHttpException
     */
    public function run()
    {
        $model = new $this->modelClass();
        $oldAccessToken = $model->getAuthKey();
        $userModel = RestUserEntity::findIdentityByAccessToken($oldAccessToken, HttpBearerAuth::className());
        $userId = $userModel->id;
        $currentRefreshToken = \Yii::$app->getRequest()->getBodyParam('refresh_token');

        $user = RestUserEntity::findOne(['refresh_token' => $currentRefreshToken, 'id' => $userId]);

        if (!$user) {
            throw new NotFoundHttpException('Пользователь с таким токеном не найден.');
        }
        $transaction = \Yii::$app->db->beginTransaction();
        try {
            $user->addBlackListToken($oldAccessToken);
            $newAccessToken = $user->getJWT();

            $transaction->commit();

            return $this->setResponse(201, 'New token is created', [
                'access_token'  => $newAccessToken,
                'refresh_token' => $user->refresh_token,
                'exp'  => RestUserEntity::getPayload($newAccessToken, 'exp'),
                'user' => [
                    'id'         => $user->getId(),
                    'email'      => $user->email,
                    'role'       => $user->getUserRole($user->id),
                    'created_at' => $user->created_at
                ]
            ]);

        } catch (ExceptionDb $e) {
            $transaction->rollBack();
            throw new HttpException(422, $e->getMessage());
        } catch (Exception $e){
            $transaction->rollBack();
            Yii::error(ErrorHandler::convertExceptionToString($e));
            throw new ServerErrorHttpException('Произошла ошибка при генерации нового токена.');
        }
    }

}

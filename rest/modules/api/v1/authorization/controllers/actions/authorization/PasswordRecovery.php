<?php
/**
 * Created by PhpStorm.
 * User: dima
 * Date: 16.02.18
 * Time: 8:10
 */

namespace rest\modules\api\v1\authorization\controllers\actions\authorization;


use Codeception\Coverage\Subscriber\RemoteServer;
use common\behaviors\ValidatePostParameters;
use rest\modules\api\v1\authorization\models\RestUserEntity;
use Yii;
use yii\base\Exception;
use yii\web\Response;
use yii\rest\Action;
use yii\web\HttpException;

class PasswordRecovery extends Action
{
    public function run()
    {
        $email = Yii::$app->request->post('email');
        $user = new RestUserEntity();
        if (!empty($email)) {
            $user = $user->getUserByEmail($email);
        } elseif
            (!empty($phoneNumber)) {
            $user = $user->getUserByPhoneNumber($phoneNumber);
        }
        $user->scenario = RestUserEntity::SCENARIO_RECOVERY_PWD;
        try {
            if ($user->recoveryCode(Yii::$app->request->post())) {
                $response = Yii::$app->getResponse();
                $response->setStatusCode('200', 'OK');
                $response->format = Response::FORMAT_JSON;
                return $response->content = [
                    'status' => $response->statusCode,
                    'message' => 'Восстановления пароля прошло успешно'
                ];
            }
        } catch (Exception $e) {
            throw new HttpException(422, $e->getMessage());
        }
    }

}
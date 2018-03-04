<?php
/**
 * Created by PhpStorm.
 * User: dima
 * Date: 16.02.18
 * Time: 8:10
 */

namespace rest\modules\api\v1\authorization\controllers\actions\authorization;

use rest\behaviors\ResponseBehavior;
use rest\modules\api\v1\authorization\models\RestUserEntity;
use Yii;
use yii\base\Exception;
use yii\rest\Action;
use yii\web\BadRequestHttpException;
use yii\web\HttpException;

/**
 * Class PasswordRecovery
 * @package rest\modules\api\v1\authorization\controllers\actions\authorization
 */
class PasswordRecovery extends Action
{
    /**
     * @return array
     */
    public function behaviors(): array
    {
        $behaviors = parent::behaviors();

        $behaviors['responseBehavior'] = ResponseBehavior::class;

        return $behaviors;
    }

    /**
     * Password recovery action
     *
     * @return array
     * @throws BadRequestHttpException
     * @throws HttpException
     */
    public function run()
    {
        $email = Yii::$app->request->post('email');
        $phoneNumber = \Yii::$app->request->post('phone_number');
        $user = new RestUserEntity();
        if (!empty($email)) {
            $user = $user->getUserByEmail($email);
        } elseif (!empty($phoneNumber)) {
            $user = $user->getUserByPhoneNumber($phoneNumber);
        } else {
            throw new BadRequestHttpException('Укажите email или номер телефона.');
        }
        $user->scenario = RestUserEntity::SCENARIO_RECOVERY_PWD;
        try {
            if ($user->recoveryCode(Yii::$app->request->post())) {
                /** @var $this ResponseBehavior */
                return $this->setResponse(
                    200, 'Восстановления пароля прошло успешно'
                );
            }
        } catch (Exception $e) {
            throw new HttpException(422, $e->getMessage());
        }
    }

}
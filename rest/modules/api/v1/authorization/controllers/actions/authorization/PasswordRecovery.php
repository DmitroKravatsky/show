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
     * @SWG\Post(path="/authorization/password-recovery",
     *      tags={"Authorization module"},
     *      summary="User password recovery",
     *      description="User password-recovery",
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *          in = "formData",
     *          name = "email",
     *          description = "User email",
     *          required = false,
     *          type = "string"
     *      ),
     *      @SWG\Parameter(
     *          in = "formData",
     *          name = "phone_number",
     *          description = "User phone number",
     *          required = false,
     *          type = "string"
     *      ),
     *      @SWG\Parameter(
     *          in = "formData",
     *          name = "password",
     *          description = "User new password",
     *          required = true,
     *          type = "string"
     *      ),
     *      @SWG\Parameter(
     *          in = "formData",
     *          name = "confirm_password",
     *          description = "User password confirmation",
     *          required = true,
     *          type = "string"
     *      ),
     *      @SWG\Parameter(
     *          in = "formData",
     *          name = "recovery_code",
     *          description = "User phone number",
     *          required = true,
     *          type = "integer"
     *      ),
     *      @SWG\Response(
     *         response = 200,
     *         description = "success",
     *         @SWG\Schema(
     *              type="object",
     *              @SWG\Property(property="status", type="integer", description="Status code"),
     *              @SWG\Property(property="message", type="string", description="Status message"),
     *         ),
     *         examples = {
     *              "status": 200,
     *              "message": "Восстановления пароля прошло успешно.",
     *              "data": {
     *              }
     *         }
     *     ),
     *      @SWG\Response (
     *         response = 422,
     *         description = "Validation Error"
     *     ),
     *     @SWG\Response(
     *         response = 500,
     *         description = "Internal Server Error"
     *     )
     * )
     *
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
                    200, 'Password recovery has been ended successfully '
                );
            }
        } catch (Exception $e) {
            throw new HttpException(422, $e->getMessage());
        }
    }

}
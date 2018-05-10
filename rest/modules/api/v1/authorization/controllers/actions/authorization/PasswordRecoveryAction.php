<?php
/**
 * Created by PhpStorm.
 * User: dima
 * Date: 16.02.18
 * Time: 8:10
 */

namespace rest\modules\api\v1\authorization\controllers\actions\authorization;

use common\behaviors\ValidatePostParameters;
use rest\modules\api\v1\authorization\controllers\AuthorizationController;
use rest\modules\api\v1\authorization\models\RestUserEntity;
use Yii;
use yii\rest\Action;
use yii\web\BadRequestHttpException;
use yii\web\HttpException;
use yii\web\ServerErrorHttpException;
use yii\web\UnprocessableEntityHttpException;

/**
 * Class PasswordRecovery
 * @package rest\modules\api\v1\authorization\controllers\actions\authorization
 *
 * @mixin ValidatePostParameters
 */
class PasswordRecoveryAction extends Action
{
    /**
     * @var array
     */
    public $params = [];

    /** @var  AuthorizationController */
    public $controller;

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'reportParams' => [
                'class'       => ValidatePostParameters::class,
                'inputParams' => ['password', 'confirm_password', 'recovery_code', 'phone_number']
            ]
        ];
    }

    /**
     * @inheritdoc
     */
    protected function beforeRun()
    {
        $this->validationParams();

        return parent::beforeRun();
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
     *          name = "phone_number",
     *          description = "User phone number",
     *          required = true,
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
     *          description = "User password recovery code",
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
     *              "message": "Password recovery was successfully ended",
     *              "data": {
     *              }
     *         }
     *     ),
     *     @SWG\Response (
     *         response = 400,
     *         description = "Not enough income params"
     *     ),
     *     @SWG\Response (
     *         response = 404,
     *         description = "User not found"
     *     ),
     *     @SWG\Response (
     *         response = 422,
     *         description = "Validation Error"
     *     ),
     *     @SWG\Response(
     *         response = 500,
     *         description = "Internal Server Error"
     *     )
     * )
     *
     *
     * Password recovery action
     *
     * @return array
     * @throws BadRequestHttpException
     * @throws HttpException
     */
    public function run()
    {
        $phoneNumber = \Yii::$app->request->post('phone_number');
        $user = new RestUserEntity();
        $user = $user->getUserByPhoneNumber($phoneNumber);

        $user->scenario = RestUserEntity::SCENARIO_RECOVERY_PWD;
        try {
            if ($user->recoveryCode(Yii::$app->request->post())) { // todo почему метод не подсвечивается

                $response = \Yii::$app->getResponse()->setStatusCode(200, 'Password recovery has been ended successfully');
                return $response->content = [
                    'status' => $response->statusCode,
                    'message' => 'Password recovery has been ended successfully'
                ];
            }
        } catch (UnprocessableEntityHttpException $e) {
            throw new UnprocessableEntityHttpException($e->getMessage());
        } catch (ServerErrorHttpException $e) {
            throw new ServerErrorHttpException($e->getMessage());
        }
    }
}

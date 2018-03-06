<?php

namespace rest\modules\api\v1\authorization\controllers\actions\authorization;

use rest\modules\api\v1\authorization\controllers\AuthorizationController;
use rest\modules\api\v1\authorization\models\RestUserEntity;
use yii\rest\Action;
use yii\web\NotFoundHttpException;
use yii\web\UnauthorizedHttpException;
use yii\web\UnprocessableEntityHttpException;
use Yii;

/**
 * Class LoginAction
 * @package rest\modules\api\v1\authorization\controllers\actions\authorization
 */
class LoginAction extends Action
{
    /** @var  AuthorizationController */
    public $controller;

    /**
     * @SWG\Post(path="/authorization/login",
     *      tags={"Authorization module"},
     *      summary="User login",
     *      description="User login",
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
     *          description = "User password",
     *          required = true,
     *          type = "string"
     *      ),
     *      @SWG\Response(
     *         response = 200,
     *         description = "success",
     *         @SWG\Schema(
     *              type="object",
     *              @SWG\Property(property="status", type="integer", description="Status code"),
     *              @SWG\Property(property="message", type="string", description="Status message"),
     *              @SWG\Property(property="data", type="object",
     *                  @SWG\Property(property="access_token", type="string", description="access token")
     *              ),
     *         ),
     *         examples = {
     *              "status": 200,
     *              "message": "Авторизация прошла успешно.",
     *              "data": {
     *                  "access_token": "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJqdGkiOjExLCJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiIsImV4cCI6MTUxODE3MjA2NX0.YpKRykzIfEJI5RhB5HYd5pDdBy8CWrA5OinJYGyVmew"
     *              }
     *         }
     *     ),
     *      @SWG\Response(
     *         response = 404,
     *         description = "User not found"
     *     ),
     *     @SWG\Response (
     *         response = 422,
     *         description = "Validation Error"
     *     ),
     *     @SWG\Response (
     *         response = 401,
     *         description = "Unauthorized Error"
     *     )
     * )
     *
     * Login action
     *
     * @return array
     * @throws NotFoundHttpException
     * @throws UnauthorizedHttpException
     * @throws UnprocessableEntityHttpException
     */
    public function run()
    {
        try {
            /** @var RestUserEntity $userModel */
            $userModel = new $this->modelClass;

            if ($user = $userModel->login(Yii::$app->request->bodyParams)) {
                return $this->controller->setResponse(
                    200, 'Авторизация прошла успешно.', ['access_token' => $user->getJWT(['user_id' => $user->id])]);
            }

            throw new UnauthorizedHttpException();
        } catch (UnprocessableEntityHttpException $e) {
            throw new UnprocessableEntityHttpException($e->getMessage());
        } catch (NotFoundHttpException $e) {
            throw new NotFoundHttpException($e->getMessage());
        } catch (\Exception $e) {
            Yii::error($e->getMessage());
            throw new UnauthorizedHttpException('Ошибка авторизации.');
        }
    }
}
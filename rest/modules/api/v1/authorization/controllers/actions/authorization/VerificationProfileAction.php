<?php
/**
 * Created by PhpStorm.
 * User: dima
 * Date: 02.03.18
 * Time: 22:16
 */

namespace rest\modules\api\v1\authorization\controllers\actions\authorization;
// todo
use rest\modules\api\v1\authorization\controllers\AuthorizationController;
use rest\modules\api\v1\authorization\models\RestUserEntity;
use yii\rest\Action;
use rest\behaviors\ResponseBehavior;
use yii\web\NotFoundHttpException;
use yii\web\ServerErrorHttpException;
use yii\web\UnprocessableEntityHttpException;

class VerificationProfileAction extends Action
{
    /** @var  AuthorizationController */
    public $controller;

    /**
     * VerificationProfile action
     *
     * @SWG\Post(path="/authorization/verification-profile",
     *      tags={"Authorization module"},
     *      summary="User verification-profile",
     *      description="User verification-profile",
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *        in = "formData",
     *        name = "Phone number",
     *        description = "User phone number",
     *        required = true,
     *        type = "string"
     *      ),
     *      @SWG\Parameter(
     *          in = "formData",
     *          name = "verification_code",
     *          description = "User verification code",
     *          required = true,
     *          type = "string"
     *      ),
     *      @SWG\Response(
     *         response = 201,
     *         description = "success",
     *         @SWG\Schema(
     *              type="object",
     *              @SWG\Property(property="status", type="integer", description="Status code"),
     *              @SWG\Property(property="message", type="string", description="Status message"),
     *              @SWG\Property(property="data", type="object")
     *         ),
     *         examples = {
     *              "status": 201,
     *              "message": "Your profile has been verified",
     *              "data": {
     *              }
     *         }
     *     ),
     *     @SWG\Response(
     *         response = 404,
     *         description = "User is not found"
     *     ),
     *     @SWG\Response (
     *         response = 422,
     *         description = "Validation Error"
     *     ),
     *     @SWG\Response(
     *         response = 500,
     *         description = "Internal sever error"
     *     )
     * )
     *
     *
     * Verify user's account
     *
     * @return array
     * @throws NotFoundHttpException
     * @throws UnprocessableEntityHttpException
     * @throws ServerErrorHttpException
     */
    public function run()
    {
        /** @var RestUserEntity $model */
        $model = new $this->modelClass;
        $user  = $model->verifyUser(\Yii::$app->request->bodyParams);

        \Yii::$app->getResponse()->setStatusCode(200, 'You have been successfully logout');
        return [
            /** @var RestUserEntity $user */
            'id'            => $user->id, // todo не должно такого быть что не подсвечивает. Добавить аннотации для $user
            'access_token'  => $user->getJWT(),
            'refresh_token' => $user->refresh_token,
        ];
    }
}
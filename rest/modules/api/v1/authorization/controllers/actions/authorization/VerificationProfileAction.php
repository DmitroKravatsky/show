<?php
/**
 * Created by PhpStorm.
 * User: dima
 * Date: 02.03.18
 * Time: 22:16
 */

namespace rest\modules\api\v1\authorization\controllers\actions\authorization;

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
     *        in = "header",
     *        name = "Authorization",
     *        description = "Authorization: Bearer &lt;token&gt;",
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
     *         response = 200,
     *         description = "success",
     *         @SWG\Schema(
     *              type="object",
     *              @SWG\Property(property="status", type="integer", description="Status code"),
     *              @SWG\Property(property="message", type="string", description="Status message"),
     *              @SWG\Property(property="data", type="object")
     *         ),
     *         examples = {
     *              "status": 201,
     *              "message": "Ваш профиль подтвержден",
     *              "data": {
     *              }
     *         }
     *     ),
     *      @SWG\Response(
     *         response = 401,
     *         description = "Invalid credentials"
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
        $model->verifyUser(\Yii::$app->request->bodyParams);

        /** @var ResponseBehavior */
        return $this->controller->setResponse(201, 'Your profile has been verified');
    }
}
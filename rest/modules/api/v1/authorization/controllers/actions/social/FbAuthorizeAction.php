<?php
namespace rest\modules\api\v1\authorization\controllers\actions\social;

use common\behaviors\ValidatePostParameters;
use rest\modules\api\v1\authorization\controllers\SocialController;
use rest\modules\api\v1\authorization\models\RestUserEntity;
use yii\rest\Action;

/**
 * Class FbAuthorizeAction
 * @package rest\modules\api\v1\authorization\controllers\actions\social
 *
 * @mixin ValidatePostParameters
 */
class FbAuthorizeAction extends Action
{
    /** @var  $controller SocialController */
    public $controller;

    /**
     * @var array
     */
    public $params = [];

    /**
     * @return array
     */
    public function behaviors(): array
    {
        return [
            'reportParams' => [
                'class'       => ValidatePostParameters::class,
                'inputParams' => [
                    'access_token', 'terms_condition'
                ]
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function beforeRun()
    {
        $this->validationParams();

        return parent::beforeRun();
    }

    /**
     * Facebook authorization action
     *
     * @SWG\Post(path="/social/fb-authorize",
     *      tags={"Authorization module"},
     *      summary="User facebook authorization",
     *      description="User authorization via facebook",
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *          in = "formData",
     *          name = "access_token",
     *          description = "user's token on facebook",
     *          required = true,
     *          type = "string"
     *      ),
     *      @SWG\Parameter(
     *          in = "formData",
     *          name = "terms_condition",
     *          description = "Terms condition",
     *          required = true,
     *          type = "integer",
     *          enum = {0, 1}
     *      ),
     *      @SWG\Response(
     *         response = 201,
     *         description = "success",
     *         @SWG\Schema(
     *              type="object",
     *              @SWG\Property(property="status", type="integer", description="Status code"),
     *              @SWG\Property(property="message", type="string", description="Status message"),
     *              @SWG\Property(property="data", type="object",
     *                  @SWG\Property(property="user_id", type="integer", description="user id"),
     *                  @SWG\Property(property="access_token", type="string", description="access token"),
     *                  @SWG\Property(property="refresh_token", type="string", description="refresh token")
     *              ),
     *         ),
     *         examples = {
     *              "status": 201,
     *              "message": "You have been successfully authorized",
     *              "data": {
     *                  "user_id": "124",
     *                  "access_token": "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJqdGkiOjExLCJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiIsImV4cCI6MTUxODE3MjA2NX0.YpKRykzIfEJI5RhB5HYd5pDdBy8CWrA5OinJYGyVmew",
     *                  "exp": 1536224824,
     *                  "refresh_token": "b_pZ4P3Z10BbEwe0A6GE2Aij8cfDDAEc"
     *              }
     *         }
     *     ),
     *      @SWG\Response (
     *         response = 400,
     *         description = "Bad request"
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
     /**
     *
     * @return array
     * @throws \yii\web\ServerErrorHttpException
     * @throws \yii\web\UnprocessableEntityHttpException
     */
    public function run()
    {
        /** @var RestUserEntity $userModel */
        $userModel = new $this->modelClass;
        $user = $userModel->fbAuthorization(\Yii::$app->request->bodyParams);
        $response = \Yii::$app->getResponse()->setStatusCode(200);
        return [
            'status'  => $response->statusCode,
            'message' => \Yii::t('app', 'Authorization was successful'),
            'data'    => [
                'user_id' => $user->id,
                'access_token'  => $accessToken = $user->getJWT(['user_id' => $user->id]),
                'exp'           => RestUserEntity::getPayload($accessToken, 'exp'),
                'refresh_token' => $user->refresh_token
            ]
        ];
    }
}

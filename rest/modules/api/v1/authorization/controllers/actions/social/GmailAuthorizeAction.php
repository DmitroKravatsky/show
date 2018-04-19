<?php

namespace rest\modules\api\v1\authorization\controllers\actions\social;

use common\behaviors\ValidatePostParameters;
use rest\modules\api\v1\authorization\controllers\SocialController;
use rest\modules\api\v1\authorization\models\RestUserEntity;
use yii\rest\Action;

/**
 * Class GmailAuthorizationAction
 * @package rest\modules\api\v1\authorization\controllers\actions\social
 */
class GmailAuthorizeAction extends Action
{
    /** @var  SocialController */
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
     * @return bool
     *
     * @throws \yii\web\BadRequestHttpException
     */
    protected function beforeRun(): bool
    {
        $this->validationParams(); // todo
        return parent::beforeRun();
    }

    /**
     * Gmail authorization action
     *
     * @SWG\Post(path="/social/gmail-authorize",
     *      tags={"Authorization module"},
     *      summary="User gmail authorization",
     *      description="User authorization via gmail",
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *          in = "formData",
     *          name = "token",
     *          description = "user's token on gmail",
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
     *                  @SWG\Property(property="id",            type="integer", description="user id")
     *                  @SWG\Property(property="access_token",  type="string", description="access token")
     *                  @SWG\Property(property="refresh_token", type="string", description="access token")
     *              ),
     *         ),
     *         examples = {
     *              "status": 201,
     *              "message": "You have been authorized",
     *              "data": {
     *                  "id": "93",
     *                  "access_token": "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJqdGkiOjExLCJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiIsImV4cCI6MTUxODE3MjA2NX0.YpKRykzIfEJI5RhB5HYd5pDdBy8CWrA5OinJYGyVmew"
     *                  "refresh_token": "aRVDpKr1VmknVPwRmMlwje9D5B6BKhcgaRVDpKr1VmknVPwRmMlwje9D5B6BKhcgaRVDpKr1VmknVPwRmMlwje9D5B6BKhcg"
     *              }
     *         }
     *     ),
     *     @SWG\Response (
     *         response = 400,
     *         description = "Bad request"
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
     * @return array|bool
     *
     * @throws \yii\web\ServerErrorHttpException
     * @throws \yii\web\UnprocessableEntityHttpException
     */
    public function run()
    {
        /** @var RestUserEntity $model */
        $model = new $this->modelClass;
        $user = $model->gmailAuthorization(\Yii::$app->request->bodyParams);

        return $this->controller->setResponse(
            201, 'You have been authorized', [
                'id'            => $user->id,
                'access_token'  => $user->getJWT(['user_id' => $user->id]),
                'refresh_token' => $user->refresh_token,
            ]
        );
    }
}
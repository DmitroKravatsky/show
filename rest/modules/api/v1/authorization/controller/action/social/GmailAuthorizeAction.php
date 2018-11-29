<?php

declare(strict_types=1);

namespace rest\modules\api\v1\authorization\controller\action\social;

use Yii;
use rest\modules\api\v1\authorization\controller\SocialController;
use rest\modules\api\v1\authorization\model\social\GmailAuthorizationRequestModel;
use yii\web\ServerErrorHttpException;
use yii\rest\Action;

class GmailAuthorizeAction extends Action
{
    /** @var  SocialController */
    public $controller;

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
     *          name = "access_token",
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
     *         response = 200,
     *         description = "success",
     *         @SWG\Schema(
     *              type="object",
     *              @SWG\Property(property="status", type="integer", description="Status code"),
     *              @SWG\Property(property="message", type="string", description="Status message"),
     *              @SWG\Property(property="data", type="object",
     *                  @SWG\Property(property="id",            type="integer", description="user id"),
     *                  @SWG\Property(property="access_token",  type="string", description="access token"),
     *                  @SWG\Property(property="refresh_token", type="string", description="access token")
     *              ),
     *         ),
     *         examples = {
     *              "status": 200,
     *              "message": "Authorization was successfully ended.",
     *              "data": {
     *                  "user_id": 93,
     *                  "access_token": "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJqdGkiOjExLCJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiIsImV4cCI6MTUxODE3MjA2NX0.YpKRykzIfEJI5RhB5HYd5pDdBy8CWrA5OinJYGyVmew",
     *                  "exp": 1536224824,
     *                  "refresh_token": "aRVDpKr1VmknVPwRmMlwje9D5B6BKhcgaRVDpKr1VmknVPwRmMlwje9D5B6BKhcgaRVDpKr1VmknVPwRmMlwje9D5B6BKhcg"
     *              }
     *         }
     *     ),
     *      @SWG\Response (
     *         response = 401,
     *         description = "Unauthorized"
     *     ),
     *      @SWG\Response (
     *         response = 422,
     *         description = "Unprocessable Entity"
     *     ),
     *     @SWG\Response(
     *         response = 500,
     *         description = "Internal Server Error"
     *     )
     * )
     *
     * @return array
     *
     * @throws \yii\web\ServerErrorHttpException
     * @throws \yii\web\UnprocessableEntityHttpException
     */
    public function run(): array
    {
        $model = new GmailAuthorizationRequestModel();
        if (!$model->load(Yii::$app->request->bodyParams, '') || !$model->validate()) {
            $model->throwModelException($model->errors);
        }

        try {
            return [
                'status' => Yii::$app->response->getStatusCode(),
                'message' => 'Authorization was successfully ended.',
                'data' => $this->controller->service->gmailAuthorization($model)
            ];
        } catch (\Exception $e) {
            throw new ServerErrorHttpException('Something is wrong, please try again later.');
        }
    }
}

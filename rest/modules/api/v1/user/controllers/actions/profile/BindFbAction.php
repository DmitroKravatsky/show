<?php

namespace rest\modules\api\v1\user\controllers\actions\profile;


use common\behaviors\ValidatePostParameters;
use common\models\userSocial\UserSocial;
use yii\rest\Action;
use Yii;

/**
 * Class BindFbAction
 * @mixin ValidatePostParameters
 */
class BindFbAction extends Action
{
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
                    'access_token',
                ]
            ],
        ];
    }

    /**
     * @return bool
     * @throws \yii\web\BadRequestHttpException
     */
    protected function beforeRun(): bool
    {
        $this->validationParams();
        return parent::beforeRun();
    }

    /**
     * Bind a facebook account
     *
     * @SWG\Post(path="/user/profile/bind-fb",
     *      tags={"User module"},
     *      summary="Bind a facebook account",
     *      description="Bind a facebook account to the current profile",
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
     *          name = "access_token",
     *          description = "user's token on facebook",
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
     *                  @SWG\Property(property="id", type="integer", description="User Profile id"),
     *                  @SWG\Property(property="name", type="string", description="User name"),
     *                  @SWG\Property(property="last_name", type="string", description="User last name"),
     *                  @SWG\Property(property="avatar", type="string", description="User avatar"),
     *                  @SWG\Property(property="email", type="string", description="User email"),
     *                  @SWG\Property(property="phone_number", type="string", description="User phone number"),
     *                  @SWG\Property(property="source", type="string", description="User social network"),
     *                  @SWG\Property(
     *                      property="is_fb_auth",
     *                      type="boolean",
     *                      description="Marks if user is social auth"
     *                  ),
     *                  @SWG\Property(
     *                      property="is_gmail_auth",
     *                      type="boolean",
     *                      description="Marks if user is social auth"
     *                  )
     *              ),
     *         ),
     *         examples = {
     *              "status": 200,
     *              "message": "Социальная сеть успешно привязана.",
     *              "data": {
     *                  "id": 6,
     *                  "name": "John",
     *                  "last_name": "Smith",
     *                  "avatar": null,
     *                  "email": "smith@gmail.com",
     *                  "phone_number": null,
     *                  "is_fb_auth": true,
     *                  "is_gmail_auth": false
     *              }
     *         }
     *     ),
     *     @SWG\Response (
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
     * @return array
     *
     * @throws \yii\web\ServerErrorHttpException
     * @throws \yii\web\BadRequestHttpException
     */
    public function run()
    {
        /** @var UserSocial $model */
        $model = new $this->modelClass;
        $result = $model->bindFb(Yii::$app->request->post('access_token'));
        $response = \Yii::$app->getResponse()->setStatusCode(200);

        return [
            'status'  => $response->statusCode,
            'message' => \Yii::t('app', 'Социальная сеть успешно привязана.'),
            'data'    => $result
        ];
    }
}

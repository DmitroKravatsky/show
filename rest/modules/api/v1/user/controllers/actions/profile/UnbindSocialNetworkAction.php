<?php

namespace rest\modules\api\v1\user\controllers\actions\profile;


use common\behaviors\ValidatePostParameters;
use common\models\userSocial\UserSocial;
use yii\rest\Action;
use Yii;

/**
 * Class UnbindSocialNetworkAction
 * @mixin ValidatePostParameters
 */
class UnbindSocialNetworkAction extends Action
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
                    'source_name',
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
     * @SWG\Post(path="/user/profile/unbind-social-network",
     *      tags={"User module"},
     *      summary="Unbind social network",
     *      description="Unbind social network",
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
     *          name = "source_name",
     *          description = "social network name",
     *          required = true,
     *          type = "integer",
     *          enum = {"fb", "gmail"}
     *      ),
     *      @SWG\Response(
     *         response = 200,
     *         description = "OK",
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
     *                  @SWG\Property(property="is_deleted", type="boolean", description="Is user deleted"),
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
     *              "message": "Social network was successfully unbind",
     *              "data": {
     *                  "user_id": 6,
     *                  "name": "John",
     *                  "last_name": "Smith",
     *                  "avatar": null,
     *                  "email": "smith@gmail.com",
     *                  "phone_number": null,
     *                  "is_fb_auth": true,
     *                  "is_gmail_auth": false,
     *                  "is_deleted": false
     *              }
     *         }
     *     ),
     *     @SWG\Response (
     *         response = 400,
     *         description = "Bad request"
     *     ),
     *     @SWG\Response(
     *         response = 403,
     *         description = "Forbidden"
     *     ),
     *     @SWG\Response (
     *         response = 404,
     *         description = "Not found"
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
     * @throws \yii\web\NotFoundHttpException;
     */
    public function run()
    {
        /** @var UserSocial $model */
        $model = new $this->modelClass;
        $result = $model->unbindSocialNetwork(Yii::$app->request->post('source_name'));
        $response = \Yii::$app->getResponse()->setStatusCode(200);

        return [
            'status'  => $response->statusCode,
            'message' => \Yii::t('app', 'Social network was successfully unbind'),
            'data'    => $result
        ];
    }
}

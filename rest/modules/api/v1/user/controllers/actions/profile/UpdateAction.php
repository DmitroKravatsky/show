<?php

namespace rest\modules\api\v1\user\controllers\actions\profile;

use common\models\userProfile\UserProfileEntity;
use rest\modules\api\v1\user\controllers\UserProfileController;
use yii\rest\Action;
use yii\web\ServerErrorHttpException;

/**
 * Class UpdateAction
 * @package rest\modules\api\v1\user\controllers\actions\profile
 */
class UpdateAction extends Action
{
    /** @var  UserProfileController */
    public $controller;

    /**
     * Updates an existing model
     *
     * @SWG\Put(path="/user/profile",
     *      tags={"User module"},
     *      summary="Updates user profile",
     *      description="Updates user profile",
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
     *          name = "name",
     *          description = "User name",
     *          required = false,
     *          type = "string"
     *      ),
     *      @SWG\Parameter(
     *          in = "formData",
     *          name = "last_name",
     *          description = "User last name",
     *          required = false,
     *          type = "string"
     *      ),
     *      @SWG\Parameter(
     *          in = "formData",
     *          name = "avatar",
     *          description = "User avatar in base64 format",
     *          required = false,
     *          type = "string",
     *          format = "byte"
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
     *                  @SWG\Property(property="avatar", type="string", description="User avatar")
     *              ),
     *         ),
     *         examples = {
     *              "status": 200,
     *              "message": "'Profile was successfully edited'",
     *              "data": {
     *                  "id": 6,
     *                  "name": "John",
     *                  "last_name": "Smith",
     *                  "avatar": "https://bigbizbucket.s3.amazonaws.com/user_profile/user-125/Vwd4V1c_rAyWUsWjaQUfwu87PaZnb1VF.jpeg"
     *              }
     *         }
     *     ),
     *     @SWG\Response (
     *         response = 422,
     *         description = "Validation Error"
     *     ),
     *     @SWG\Response (
     *         response = 401,
     *         description = "Invalid credentials or Expired token"
     *     ),
     *     @SWG\Response(
     *         response = 500,
     *         description = "Server Error"
     *     )
     * )
     *
     * @return array
     *
     * @throws ServerErrorHttpException
     * @throws \yii\web\UnprocessableEntityHttpException
     */
    public function run(): array 
    {
        /** @var UserProfileEntity $model */
        $model = new $this->modelClass;
        $userProfile = $model->updateProfile(\Yii::$app->request->bodyParams);

        $response = \Yii::$app->response->setStatusCode(200);
        return [
            'status'  => $response->statusCode,
            'message' => 'Profile was successfully edited',
            'data'    => $userProfile->getAttributes((['id', 'name', 'last_name', 'avatar']))
        ];
    }
}

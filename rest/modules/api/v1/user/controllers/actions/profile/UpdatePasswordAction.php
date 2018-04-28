<?php

namespace rest\modules\api\v1\user\controllers\actions\profile;

use rest\modules\api\v1\authorization\models\RestUserEntity;
use rest\modules\api\v1\user\controllers\UserProfileController;
use yii\rest\Action;

/**
 * Class UpdatePasswordAction
 * @package rest\modules\api\v1\user\controllers\actions\profile
 */
class UpdatePasswordAction extends Action
{
    /** @var  UserProfileController */
    public $controller;

    /**
     * Updates User password
     *
     * @SWG\Put(path="/user/profile/update-password",
     *      tags={"User module"},
     *      summary="Updates user password",
     *      description="Updates user password",
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
     *          name = "new_password",
     *          description = "New user's password",
     *          required = true,
     *          type = "string"
     *      ),
     *      @SWG\Parameter(
     *          in = "formData",
     *          name = "current_password",
     *          description = "User's current password",
     *          required = true,
     *          type = "string"
     *      ),
     *      @SWG\Parameter(
     *          in = "formData",
     *          name = "confirm_password",
     *          description = "Confirm password",
     *          required = true,
     *          type = "string"
     *      ),
     *      @SWG\Response(
     *         response = 200,
     *         description = "success",
     *          @SWG\Schema(
     *              type="object",
     *              @SWG\Property(property="status", type="integer", description="Status code"),
     *              @SWG\Property(property="message", type="string", description="Status message"),
     *         ),
     *         examples = {
     *              "status": 200,
     *              "message": "Password was successfully changed",
     *         }
     *     ),
     *     @SWG\Response (
     *         response = 422,
     *         description = "Validation Error"
     *     ),
     *     @SWG\Response (
     *         response = 401,
     *         description = "Invalid credentials or Expired token"
     *     )
     * )
     *
     * @return array
     */
    public function run(): array
    {
        $userModel = new RestUserEntity();
        $userModel->updatePassword(\Yii::$app->request->bodyParams);

        \Yii::$app->response->setStatusCode(200, 'Password was successfully changed');
        return [
            'status' => \Yii::$app->response->getStatusCode(),
            'message' => 'Password was successfully changed'
        ];
    }
}
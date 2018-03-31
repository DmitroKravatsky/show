<?php
/**
 * Created by PhpStorm.
 * User: dima
 * Date: 31.03.18
 * Time: 0:23
 */

namespace rest\modules\api\v1\authorization\controllers\actions\authorization;


use rest\behaviors\ResponseBehavior;
use yii\rest\Action;
use rest\modules\api\v1\authorization\controllers\AuthorizationController;
use rest\modules\api\v1\authorization\models\RestUserEntity;
use yii\web\ServerErrorHttpException;
use yii\web\UnauthorizedHttpException;

class LogoutAction extends Action
{
    /** @var  AuthorizationController */
    public $controller;

    /**
     * GenerateNewAccessToken action
     *
     * @SWG\Post(path="/authorization/logout",
     *      tags={"Authorization module"},
     *      summary="Generate-new-access-token for user",
     *      description="Generate new access_token for user",
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *          in = "header",
     *          name = "access_token",
     *          description = "User email",
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
     *              @SWG\Property(property="data", type="object"),
     *         ),
     *         examples = {
     *              "status": 200,
     *              "message": "You have been successfully logout",
     *              "data": {}
     *         }
     *     ),
     *     @SWG\Response(
     *         response = 401,
     *         description = "Invalid or expired access token"
     *     ),
     *     @SWG\Response(
     *         response = 500,
     *         description = "Internal Server Error"
     *     )
     * )
     *
     *
     * Generate New Access Token
     *
     * @return array
     * @throws ServerErrorHttpException
     * @throws UnauthorizedHttpException
     */
    public function run()
    {
        /** @var RestUserEntity $user */
        $user = new $this->modelClass();

        $user = $user->logout();
        /** @var ResponseBehavior */
        return $this->controller->setResponse(200, 'You have been successfully logout');
    }
}
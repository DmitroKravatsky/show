<?php
/**
 * Created by PhpStorm.
 * User: dima
 * Date: 31.03.18
 * Time: 0:23
 */

namespace rest\modules\api\v1\authorization\controllers\actions\authorization;

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
     * @SWG\Get(path="/authorization/logout",
     *      tags={"Authorization module"},
     *      summary="Logout user",
     *      description="Logout user from a system",
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *        in = "header",
     *        name = "Authorization",
     *        description = "Authorization: Bearer &lt;token&gt;",
     *        required = true,
     *        type = "string"
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
     *              "message": "Logout was completed",
     *              "data": {}
     *         }
     *     ),
     *     @SWG\Response(
     *         response = 401,
     *         description = "Invalid or expired access token"
     *     ),
     *     @SWG\Response(
     *         response = 500,
     *         description = "Server Error"
     *     )
     * )
     *
     *
     * Logout user from a system
     *
     * @return array
     * @throws ServerErrorHttpException
     * @throws UnauthorizedHttpException
     */
    public function run()
    {
        /** @var RestUserEntity $user */
        $user = new $this->modelClass();

        $user->logout();

        $response = \Yii::$app->getResponse()->setStatusCode(200);
        return $response->content = [
            'status'  => $response->statusCode,
            'message' => 'Logout was completed'
        ];
    }
}
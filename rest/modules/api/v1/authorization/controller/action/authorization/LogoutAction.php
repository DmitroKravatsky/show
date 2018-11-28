<?php

declare(strict_types=1);

namespace rest\modules\api\v1\authorization\controller\action\authorization;

use Yii;
use yii\rest\Action;
use rest\modules\api\v1\authorization\controller\AuthorizationController;
use yii\web\{
    NotFoundHttpException, ServerErrorHttpException, UnauthorizedHttpException, ErrorHandler
};

class LogoutAction extends Action
{
    /** @var  AuthorizationController */
    public $controller;

    /**
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
     *
     * @throws NotFoundHttpException
     * @throws ServerErrorHttpException
     * @throws UnauthorizedHttpException
     */
    public function run()
    {
        try {
            $this->controller->service->logout();

            return [
                'status' => Yii::$app->response->getStatusCode(),
                'message' => 'Logout was completed'
            ];
        } catch (NotFoundHttpException $e) {
            throw new NotFoundHttpException($e->getMessage());
        } catch (\Exception $e) {
            Yii::error(ErrorHandler::convertExceptionToString($e));
            throw new ServerErrorHttpException('Something is wrong, please try again later.');
        }
    }
}
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
     *         description = "success",
     *         examples = {
     *              "status": 200,
     *              "message": "Социальная сеть успешно отвязана."
     *         }
     *     ),
     *     @SWG\Response (
     *         response = 400,
     *         description = "Bad request"
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
        $model->unbindSocialNetwork(Yii::$app->request->post('source_name'));
        $response = \Yii::$app->getResponse()->setStatusCode(200);

        return [
            'status'  => $response->statusCode,
            'message' => \Yii::t('app', 'Социальная сеть успешно отвязана.'),
        ];
    }
}

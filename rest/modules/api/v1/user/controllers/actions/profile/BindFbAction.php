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
     *         examples = {
     *              "status": 200,
     *              "message": "Социальная сеть успешно привязана."
     *         }
     *     ),
     *     @SWG\Response (
     *         response = 400,
     *         description = "Bad request"
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
        $model->bindFb(Yii::$app->request->post('access_token'));
        $response = \Yii::$app->getResponse()->setStatusCode(200);

        return [
            'status'  => $response->statusCode,
            'message' => \Yii::t('app', 'Социальная сеть успешно привязана.'),
        ];
    }
}

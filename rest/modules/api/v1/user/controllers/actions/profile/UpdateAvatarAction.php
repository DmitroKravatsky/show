<?php

namespace rest\modules\api\v1\user\controllers\actions\profile;

use common\behaviors\AccessUserStatusBehavior;
use common\behaviors\ValidatePostParameters;
use common\models\userProfile\UserProfileEntity;
use yii\rest\Action;

/**
 * Class UpdateAvatarAction
 * @package rest\modules\api\v1\user\controllers\actions\profile
 *
 * @mixin ValidatePostParameters
 * @mixin AccessUserStatusBehavior
 */
class UpdateAvatarAction extends Action
{
    /**
     * @var array
     */
    public $params = [];

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'reportParams' => [
                'class'       => ValidatePostParameters::class,
                'inputParams' => ['base64_image']
            ],
            [
                'class'   => AccessUserStatusBehavior::class,
                'message' => 'Доступ запрещён.'
            ]
        ];
    }

    /**
     * @inheritdoc
     */
    protected function beforeRun()
    {
        $this->checkUserRole();
        $this->validationParams();

        return parent::beforeRun();
    }

    public function run()
    {
        /** @var  $userProfileModel UserProfileEntity */
        $userProfileModel = new $this->modelClass();
        $user = $userProfileModel->updateAvatar(\Yii::$app->request->bodyParams);

        $response = \Yii::$app->response->setStatusCode(200, 'OK');
        return [
            'status'  => $response->statusCode,
            'message' => 'Your avatar was successfully ended'
        ];

    }
}
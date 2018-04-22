<?php

namespace rest\modules\api\v1\authorization\controllers;

use rest\modules\api\v1\authorization\controllers\actions\authorization\{
    LoginAction, LoginGuestAction, LogoutAction, PasswordRecoveryAction, RegisterAction, SendRecoveryCodeAction,
    VerificationProfileAction, GenerateNewAccessTokenAction
};
use yii\filters\auth\HttpBearerAuth;
use yii\rest\Controller;
use yii\filters\VerbFilter;
use rest\modules\api\v1\authorization\models\RestUserEntity;

/**
 * Class AuthorizationController
 * @package rest\modules\api\v1\authorization\controllers
 *
 */
class AuthorizationController extends Controller
{
    /** @var RestUserEntity */
    public $modelClass = RestUserEntity::class;

    /**
     * @return array
     */
    public function behaviors(): array
    {
        $behaviors = parent::behaviors();

        $behaviors['verbs'] = [
            'class'   => VerbFilter::class,
            'actions' => [
                'register'    => ['POST'],
                'login'       => ['POST'],
                'login-guest' => ['POST'],
                'logout'      => ['GET'],
                'generate-new-access-token' => ['POST'],
                'send-recovery-code'        => ['POST'],
                'password-recovery'         => ['POST'],
                'verification-profile'      => ['POST'],
            ]
        ];

        $behaviors['bearerAuth'] = [
            'class' => HttpBearerAuth::class,
            'only'  => ['logout']
        ];

        return $behaviors;
    }

    /**
     * @return array
     */
    public function actions(): array
    {
        return [
            'register'                  => [
                'class'      => RegisterAction::class,
                'modelClass' => $this->modelClass
            ],
            'login'                     => [
                'class'      => LoginAction::class,
                'modelClass' => $this->modelClass
            ],
            'login-guest'               => [
                'class'      => LoginGuestAction::class,
                'modelClass' => $this->modelClass
            ],
            'generate-new-access-token' => [
                'class'      => GenerateNewAccessTokenAction::class,
                'modelClass' => $this->modelClass
            ],
            'send-recovery-code'        => [
                'class'      => SendRecoveryCodeAction::class,
                'modelClass' => $this->modelClass,
            ],
            'password-recovery'         => [
                'class'      => PasswordRecoveryAction::class,
                'modelClass' => $this->modelClass
            ],
            'verification-profile'      => [
                'class'      => VerificationProfileAction::class,
                'modelClass' => $this->modelClass
            ],
            'logout'                    => [
                'class'      => LogoutAction::class,
                'modelClass' => $this->modelClass
            ],
        ];
    }
}

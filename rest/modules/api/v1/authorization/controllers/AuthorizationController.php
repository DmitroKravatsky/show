<?php

namespace rest\modules\api\v1\authorization\controllers;

// todo применить php7
use rest\modules\api\v1\authorization\controllers\actions\authorization\GenerateNewAccessTokenAction;
use rest\behaviors\ResponseBehavior;
use rest\modules\api\v1\authorization\controllers\actions\authorization\LoginAction;
use rest\modules\api\v1\authorization\controllers\actions\authorization\LoginGuestAction;
use rest\modules\api\v1\authorization\controllers\actions\authorization\LogoutAction;
use rest\modules\api\v1\authorization\controllers\actions\authorization\PasswordRecoveryAction;
use rest\modules\api\v1\authorization\controllers\actions\authorization\RegisterAction;
use rest\modules\api\v1\authorization\controllers\actions\authorization\SendRecoveryCodeAction;
use rest\modules\api\v1\authorization\controllers\actions\authorization\VerificationProfileAction;
use yii\filters\auth\HttpBearerAuth;
use yii\rest\Controller;
use yii\filters\VerbFilter;
use rest\modules\api\v1\authorization\models\RestUserEntity;

/**
 * Class AuthorizationController
 * @package rest\modules\api\v1\authorization\controllers
 *
 * @mixin ResponseBehavior
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

        $behaviors['responseBehavior'] = ResponseBehavior::class; // todo для чего мы подключаем это поведение?

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
        // todo проверить чтобы везде => были выровнены. можно это дело настроить в phpstorm
        // todo показал здесь как пример
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
<?php

namespace rest\modules\api\v1\authorization\controllers;

use rest\modules\api\v1\authorization\controllers\actions\authorization\GenerateNewAccessTokenAction;
use rest\behaviors\ResponseBehavior;
use rest\modules\api\v1\authorization\controllers\actions\authorization\LoginAction;
use rest\modules\api\v1\authorization\controllers\actions\authorization\LoginGuestAction;
use rest\modules\api\v1\authorization\controllers\actions\authorization\PasswordRecovery;
use rest\modules\api\v1\authorization\controllers\actions\authorization\RegisterAction;
use rest\modules\api\v1\authorization\controllers\actions\authorization\SendRecoveryCode;
use rest\modules\api\v1\authorization\controllers\actions\authorization\VerificationProfile;
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
                'generate-new-access-token' => ['POST'],
                'send-recovery-code'        => ['POST'],
                'password-recovery'         => ['POST'],
                'verification-profile'      => ['POST'],
            ]
        ];

        $behaviors['responseBehavior'] = ResponseBehavior::class;

        $behaviors['bearerAuth'] = [
            'class' => HttpBearerAuth::class,
            'only'  => ['verification-profile']
        ];

        return $behaviors;
    }

    /**
     * @return array
     */
    public function actions(): array
    {
        return [
            'register' => [
                'class'      => RegisterAction::class,
                'modelClass' => $this->modelClass
            ],
            'login' => [
                'class'      => LoginAction::class,
                'modelClass' => $this->modelClass
            ],
            'login-guest' => [
                'class'      => LoginGuestAction::class,
                'modelClass' => $this->modelClass
            ],
            'generate-new-access-token' => [
                'class'      => GenerateNewAccessTokenAction::class,
                'modelClass' => $this->modelClass
            ],
            'send-recovery-code' => [
                'class' => SendRecoveryCode::class,
                'modelClass' => $this->modelClass,
            ],
            'password-recovery' => [
                'class' => PasswordRecovery::class,
                'modelClass' => $this->modelClass
            ],
            'verification-profile' => [
                'class' => VerificationProfile::class,
                'modelClass' => $this->modelClass
            ],
        ];
    }
}
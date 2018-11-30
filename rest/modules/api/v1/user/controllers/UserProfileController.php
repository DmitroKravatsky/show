<?php

namespace rest\modules\api\v1\user\controllers;

use common\models\userProfile\UserProfileEntity;
use common\models\userSocial\UserSocial;
use rest\modules\api\v1\authorization\entity\AuthUserEntity;
use rest\modules\api\v1\user\controllers\actions\profile\{
    BindFbAction, GetProfileAction, SendNewEmailValidationCodeAction, SendNewPhoneVerificationCodeAction, UnbindSocialNetworkAction, UpdateAction, UpdatePasswordAction, BindGmailAction, VerifyNewEmailAction, VerifyNewPhoneAction
};
use yii\rest\Controller;
use yii\filters\VerbFilter;
use yii\filters\auth\HttpBearerAuth;
use yii\filters\AccessControl;
use common\behaviors\AccessUserStatusBehavior;

/**
 * Class UserProfileController
 * @package rest\modules\api\v1\user\controllers
 * @mixin AccessUserStatusBehavior
 */
class UserProfileController extends Controller
{
    /** @var  UserProfileEntity $modelClass */
    public $modelClass = UserProfileEntity::class;

    /**
     * @return array
     */
    public function behaviors(): array
    {
        $behaviors = parent::behaviors();

        $behaviors['bearerAuth'] = [
            'class' => HttpBearerAuth::class,
        ];

        $behaviors['verbs'] = [
            'class'   => VerbFilter::class,
            'actions' => [
                'update'                         => ['PUT'],
                'get-profile'                    => ['GET'],
                'update-password'                => ['PUT'],
                'bind-gmail'                     => ['POST'],
                'bind-fb'                        => ['POST'],
                'send-new-email-validation-code' => ['POST'],
                'verify-new-email'               => ['POST'],
            ]
        ];

        $behaviors['accessControl'] = [
            'class' => AccessControl::class,
            'rules' => [
                [
                    'allow' => true,
                    'roles' => ['user'],
                ],
            ],
        ];

        $behaviors['accessUserStatus'] = [
            'class'   => AccessUserStatusBehavior::class,
            'message' => 'Доступ запрещён.'
        ];
        
        return $behaviors;
    }

    /**
     * @param \yii\base\Action $action
     * @return bool
     */
    public function beforeAction($action): bool
    {
        parent::beforeAction($action);
        $this->checkUserRole();

        return true;
    }

    /**
     * @return array
     */
    public function actions(): array
    {
        $actions = parent::actions();

        $actions['update'] = [
            'class'      => UpdateAction::class,
            'modelClass' => $this->modelClass
        ];

        $actions['get-profile'] = [
            'class'      => GetProfileAction::class,
            'modelClass' => $this->modelClass
        ];

        $actions['update-password'] = [
            'class'      => UpdatePasswordAction::class,
            'modelClass' => AuthUserEntity::class
        ];

        $actions['bind-gmail'] = [
            'class'      => BindGmailAction::class,
            'modelClass' => UserSocial::class,
        ];

        $actions['bind-fb'] = [
            'class'      => BindFbAction::class,
            'modelClass' => UserSocial::class,
        ];

        $actions['unbind-social-network'] = [
            'class'      => UnbindSocialNetworkAction::class,
            'modelClass' => UserSocial::class,
        ];

        $actions['send-new-email-validation-code'] = [
            'class'      => SendNewEmailValidationCodeAction::class,
            'modelClass' => AuthUserEntity::class,
        ];

        $actions['verify-new-email'] = [
            'class'      => VerifyNewEmailAction::class,
            'modelClass' => AuthUserEntity::class,
        ];

        $actions['send-new-phone-verification-code'] = [
            'class'      => SendNewPhoneVerificationCodeAction::class,
            'modelClass' => AuthUserEntity::class,
        ];

        $actions['verify-new-phone'] = [
            'class'      => VerifyNewPhoneAction::class,
            'modelClass' => AuthUserEntity::class,
        ];

        return $actions;
    }
}
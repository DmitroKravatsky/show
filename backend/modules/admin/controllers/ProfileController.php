<?php

namespace backend\modules\admin\controllers;

use backend\modules\admin\controllers\actions\profile\{
    UpdateAction, UpdatePasswordAction, IndexAction, UpdateAvatarAction
};
use yii\web\Controller;
use yii\filters\AccessControl;

class ProfileController extends Controller
{
    /**
     * @return array
     */
    public function behaviors(): array
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'allow'   => true,
                        'actions' => ['index', 'update', 'update-password', 'update-avatar',],
                        'roles'   => ['admin', 'manager',]
                    ],
                ],
            ],
        ];
    }

    /**
     * @return array
     */
    public function actions(): array
    {
        return [
            'index' => [
                'class' => IndexAction::class
            ],
            'update' => [
                'class' => UpdateAction::class,
            ],
            'update-password' => [
                'class' => UpdatePasswordAction::class,
            ],
            'update-avatar' => [
                'class' => UpdateAvatarAction::class,
            ],
        ];
    }
}

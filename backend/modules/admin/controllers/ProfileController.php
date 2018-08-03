<?php

namespace backend\modules\admin\controllers;

use backend\modules\admin\controllers\actions\profile\{
    UpdateAction, UpdatePasswordAction, IndexAction
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
                        'actions' => ['index', 'update', 'update-password',],
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
        ];
    }
}

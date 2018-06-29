<?php

namespace backend\modules\authorization\controllers;

use backend\modules\authorization\controllers\actions\authorization\LoginAction;
use backend\modules\authorization\controllers\actions\authorization\LogoutAction;
use yii\web\Controller;

class AuthorizationController extends Controller
{
    public function actions()
    {
        return [
            'login' => [
                'class' => LoginAction::class,
            ],
            'logout' => [
                'class' => LogoutAction::class,
            ],
        ];
    }
}
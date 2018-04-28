<?php

namespace backend\modules\authorization\controllers;

use backend\modules\authorization\controllers\actions\authorization\LoginAction;
use yii\web\Controller;

class AuthorizationController extends Controller
{
    public function actions()
    {
        return [
            'login' => [
                'class' => LoginAction::class,
            ],
        ];
    }
}
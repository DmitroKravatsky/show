<?php

namespace backend\modules\admin\controllers;

use backend\modules\admin\controllers\actions\admin\IndexAction;
use yii\web\Controller;

class AdminController extends Controller
{
    public function beforeAction($action)
    {
        if (!\Yii::$app->user->can('admin')) {
            return $this->redirect(\Yii::$app->homeUrl);
        }
        return parent::beforeAction($action);
    }

    public function actions()
    {
        return [
            'index' => [
                'class' => IndexAction::class
            ],
        ];
    }
}
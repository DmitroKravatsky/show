<?php

namespace backend\modules\admin\controllers;

use backend\modules\admin\controllers\actions\dashboard\{ IndexAction, UpdateMangerPasswordAction };
use yii\web\Controller;
use yii\filters\AccessControl;

class DashboardController extends Controller
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
                        'actions' => ['index', 'update-manager-password'],
                        'roles'   => ['admin', 'manager',]
                    ],
                ],
            ],
        ];
    }

    public function beforeAction($action)
    {
        if (!\Yii::$app->user->can('admin') && !\Yii::$app->user->can('manager')) {
            return $this->redirect(\Yii::$app->homeUrl);
        }
        return parent::beforeAction($action);
    }

    public function actions()
    {
        return [
            'index'                   => [
                'class' => IndexAction::class
            ],
            'update-manager-password' => [
                'class' => UpdateMangerPasswordAction::class
            ],
        ];
    }
}

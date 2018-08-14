<?php

namespace backend\modules\admin\controllers;

use backend\modules\admin\controllers\actions\admin\{
    DeleteManagerAction, IndexAction, InviteManagerAction, ManagersListAction,
    ReInviteManagerAction, UpdateMangerPasswordAction};
use yii\web\Controller;
use yii\filters\AccessControl;

class AdminController extends Controller
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
                    [
                        'allow'   => true,
                        'actions' => ['invite-manager', 'delete-manager', 'managers-list', 're-invite',],
                        'roles'   => ['admin',]
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
            'index'          => [
                'class' => IndexAction::class
            ],
            'invite-manager' => [
                'class' => InviteManagerAction::class
            ],
            'update-manager-password' => [
                'class' => UpdateMangerPasswordAction::class
            ],
            'delete-manager' => [
                'class' => DeleteManagerAction::class
            ],
            'managers-list'  => [
                'class' => ManagersListAction::class
            ],
            're-invite'      => [
                'class' => ReInviteManagerAction::class
            ],
        ];
    }
}

<?php

use common\helpers\UrlHelper;

$params = array_merge(
    require __DIR__ . '/../../common/config/params.php',
    require __DIR__ . '/../../common/config/params-local.php',
    require __DIR__ . '/params.php',
    require __DIR__ . '/params-local.php'
);

return [
    'id' => 'app-backend',
    'name' => 'Exchanger',
    'sourceLanguage' => 'en',
    'language' => 'ru',
    'basePath' => dirname(__DIR__),
    'controllerNamespace' => 'backend\controllers',
    'bootstrap' => ['log'],
    'modules' => [
        'admin' => [
            'class' => 'backend\modules\admin\Module',
        ],
        'authorization' => [
            'class' => 'backend\modules\authorization\Module',
        ],
        'gridview' =>  [
            'class' => '\kartik\grid\Module',
        ],
    ],
    'defaultRoute' => 'authorization/authorization/login',
    'components' => [
        'request' => [
            'class' => 'common\components\language\LanguageRequest',
            'csrfParam' => '_csrf-backend',
            'baseUrl' => '/admin',
        ],
        'user' => [
            'identityClass' => 'common\models\user\User',
            'enableAutoLogin' => true,
            'identityCookie' => ['name' => '_identity-backend', 'httpOnly' => true],
            'loginUrl' => '/admin',
            'on afterLogin' => function ($event) {
                /** @var \common\models\user\User $user */
                $user = $event->identity;
                $user->setStatusOnline(true);
                $user->setLastLogin();
            },
            'on beforeLogout' => function ($event) {
                /** @var \common\models\user\User $user */
                $user = $event->identity;
                $user->setStatusOnline(false);
                $user->setLastLogin();
            }
        ],
        'session' => [
            // this is the name of the session cookie used for login on the backend
            'name' => 'advanced-backend',
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'urlManager' => [
            'class' => 'common\components\language\LanguageUrlManager',
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'rules' => [
                'index'                                   => 'admin/dashboard/index',
                'update-manager-password'                 => 'admin/dashboard/update-manager-password',
                'manager/<action:[\w-]+>'                 => 'admin/manager/<action>',
                'manager/<action:[\w-]+>/<id:\d+>'        => 'admin/manager/<action>',
                'bid/<action:[\w-]+>'                     => 'admin/bid/<action>',
                'bid/<action:[\w-]+>/<id:\d+>'            => 'admin/bid/<action>',
                'payment-system/<action:[\w-]+>'          => 'admin/payment-system/<action>',
                'payment-system/<action:[\w-]+>/<id:\d+>' => 'admin/payment-system/<action>',
                'login'                                   => 'authorization/authorization/login',
                'logout'                                  => 'authorization/authorization/logout',
                'profile/<action:[\w-]+>'                 => 'admin/profile/<action>',
                'profile/<action:[\w-]+>/<id:\d+>'        => 'admin/profile/<action>/{id}',
                'notifications/<action:[\w-]+>'           => 'admin/notifications/<action>',
                'notifications/<action:[\w-]+>/<id:\d+>'  => 'admin/notifications/<action>',
                'review/<action:[\w-]+>'                  => 'admin/review/<action>',
                'review/<action:[\w-]+>/<id:\d+>'         => 'admin/review/<action>',
                'invite/<action:[\w-]+>'                  => 'admin/invite/<action>',
                'reserve/<action:[\w-]+>'                 => 'admin/reserve/<action>',
                'reserve/<action:[\w-]+>/<id:\d+>'        => 'admin/reserve/<action>',
                'user/<action:[\w-]+>'                    => 'admin/user/<action>',
                'exchange-rates/<action:[\w-]+>'          => 'admin/exchange-rates/<action>',
            ],
        ],
        'view' => [
            'theme' => [
                'pathMap' => [
                    '@app/views' => '@backend/views'
                ],
            ],
        ],
    ],
    'on beforeAction' => function () {
        Yii::$app->getResponse()->headers->set('X-Pjax-Url', UrlHelper::getCustomUrl());
    },
    'params' => $params,
];

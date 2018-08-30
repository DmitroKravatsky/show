<?php
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
    'language' => 'en',
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
            'loginUrl' => 'authorization/authorization/login',
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
                'index'                            => 'admin/admin/index',
                'update-manager-password'          => 'admin/admin/update-manager-password',
                'managers-list'                    => 'admin/admin/managers-list',
                'invite-manager'                   => 'admin/admin/invite-manager',
                're-invite'                        => 'admin/admin/re-invite',
                'delete-manager'                   => 'admin/admin/delete-manager',
                'manager/view/<id:\d+>'            => 'admin/manager/view',
                'bid/<action:[\w-]+>'              => 'admin/bid/<action>',
                'login'                            => 'authorization/authorization/login',
                'logout'                           => 'authorization/authorization/logout',
                'profile/<action:[\w-]+>'          => 'admin/profile/<action>',
                'bid/view/<id:\d+>'                => 'admin/bid/view',
                'bid-history/<action:[\w-]+>'      => 'admin/bid-history/<action>',
                'notifications/index'              => 'admin/notifications/index',
                'notifications/read-all'           => 'admin/notifications/read-all',
                'notifications/delete-all'         => 'admin/notifications/delete-all',
                'notification/view/<id:\d+>'       => 'admin/notifications/view',
                'review/index'                     => 'admin/review/index',
                'review/view/<id:\d+>'             => 'admin/review/view',
                'invite/<action:[\w-]+>'           => 'admin/invite/<action>',
                'reserve/<action:[\w-]+>'          => 'admin/reserve/<action>',
                'reserve/<action:[\w-]+>/<id:\d+>' => 'admin/reserve/<action>',
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
    'params' => $params,
];

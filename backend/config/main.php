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
    'basePath' => dirname(__DIR__),
    'controllerNamespace' => 'backend\controllers',
    'bootstrap' => ['log'],
    'modules' => [
        'admin' => [
            'class' => 'backend\modules\admin\Module',
        ],
        'authorization' => [
            'class' => 'backend\modules\authorization\Module',
        ]
    ],
    'defaultRoute' => 'authorization/authorization/login',
    'components' => [
        'request' => [
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
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'rules' => [
                'index'                      => 'admin/admin/index',
                'update-manager-password'    => 'admin/admin/update-manager-password',
                'managers-list'              => 'admin/admin/managers-list',
                'manager/view/<id:\d+>'      => 'admin/manager/view',
                'bid/index'                  => 'admin/bid/index',
                'login'                      => 'authorization/authorization/login',
                'invite-manager'             => 'admin/admin/invite-manager',
                'logout'                     => 'authorization/authorization/logout',
                'profile/index'              => 'admin/profile/index',
                'profile/update'             => 'admin/profile/update',
                'profile/verify'             => 'admin/profile/verify',
                'bids'                       => 'admin/bid/index',
                'bid/view/<id:\d+>'          => 'admin/bid/view',
                'bid-history'                => 'admin/bid-history/index',
                'notifications/index'        => 'admin/notifications/index',
                'notification/view/<id:\d+>' => 'admin/notifications/view',
                'review/index'               => 'admin/review/index',
                'review/view/<id:\d+>'       => 'admin/review/view',
                'invite/<action:[\w-]+>'     => 'admin/invite/<action>',
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
    'on beforeAction' => function ($event) {
        $language = Yii::$app->session->get('language', 'en');
        Yii::$app->language = $language;
    },
    'params' => $params,
];

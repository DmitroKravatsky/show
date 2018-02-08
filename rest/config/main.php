<?php

$params = array_merge(
    require(__DIR__ . '/../../common/config/params.php'),
    require(__DIR__ . '/../../common/config/params-local.php'),
    require(__DIR__ . '/params.php')
);

$routeRules = require(__DIR__ . '/routes.php');

return [
    'id'        => 'app-rest',
    'language'  => 'ru',
    'basePath'  => dirname(__DIR__),
    'bootstrap' => ['log'],
    'modules'   => [
        'api'   => [
            'class'   => 'rest\modules\api\Module',
            'modules' => [
                'v1'  => [
                    'class'   => 'rest\modules\api\v1\Module',
                    'modules' => [
                        'bid' => [
                            'class' => 'rest\modules\api\v1\bid\Module'
                        ],
                        'user' => [
                            'class' => 'rest\modules\api\v1\user\Module'
                        ],
                        'reserve' => [
                            'class' => 'rest\modules\api\v1\reserve\Module'
                        ],
                        'authorization' => [
                            'class' => 'rest\modules\api\v1\authorization\Module'
                        ],
                    ],
                ],
            ],
        ],
    ],
    'components' => [
        'response'   => [
            'format'  => yii\web\Response::FORMAT_JSON,
            'charset' => 'UTF-8'
        ],
        'request'    => [
            'baseUrl'                        => '/',
            'class'                          => '\yii\web\Request',
            'enableCookieValidation'         => false,
            'parsers' => ['application/json' => 'yii\web\JsonParser'],
        ],
        'log' => [
            'flushInterval' => 1,
            'traceLevel'    => YII_DEBUG ? 3 : 0,
            'targets'       => [
                [
                    'class'  => 'yii\log\FileTarget',
                    'levels' => ['info', 'error', 'warning', 'trace'],
                ]
            ]
        ],
        'urlManager' => [
            'enablePrettyUrl'     => true,
            'enableStrictParsing' => false,
            'showScriptName'      => false,
            'baseUrl'             => '/',
            'rules'               => $routeRules
        ],
        'user' => [
            'identityClass' => 'common\models\user\User'
        ],
    ],
    'params' => $params,
];

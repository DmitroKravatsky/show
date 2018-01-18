<?php

$params = array_merge(
    require(__DIR__ . '/../../common/config/params.php'),
    require(__DIR__ . '/../../common/config/params-local.php'),
    require(__DIR__ . '/params.php')
);

$routeRules = require(__DIR__ . '/routes.php');

return [
    'id' => 'app-rest',
    'language' => 'ru',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'components' => [
        'response' => [
            'format' => yii\web\Response::FORMAT_JSON,
            'charset' => 'UTF-8'
        ],
        'request' => [
            'baseUrl' => '/',
            'class' => '\yii\web\Request',
            'enableCookieValidation' => false,
            'parsers' => ['application/json' => 'yii\web\JsonParser'],
        ],
        'log'        => [
            'flushInterval' => 1,
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['info', 'error', 'warning', 'trace'],
                ]
            ]
        ],
        'urlManager' => [
            'enablePrettyUrl' => true,
            'enableStrictParsing' => false,
            'showScriptName' => false,
            'baseUrl' => '/',
            'rules' => $routeRules
        ]
    ],
    'params' => $params,
];

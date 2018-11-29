<?php

use rest\modules\api\v1\authorization\repository\{ AuthUserRepositoryInterface, AuthUserRepository };
use rest\modules\api\v1\authorization\service\authorization\{ AuthUserServiceInterface, AuthUserService };
use rest\modules\api\v1\authorization\service\social\{ SocialUserServiceInterface, SocialUserService };
use rest\modules\api\v1\authorization\factory\{ AuthUserFactoryInterface, AuthUserFactory };

$params = array_merge(
    require(__DIR__ . '/../../common/config/params.php'),
    require(__DIR__ . '/../../common/config/params-local.php'),
    require(__DIR__ . '/params.php')
);

$routeRules = require(__DIR__ . '/routes.php');

return [
    'id'        => 'app-rest',
    'language'  => 'en',
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
                        'review' => [
                            'class' => 'rest\modules\api\v1\review\Module'
                        ],
                        'wallet' => [
                            'class' => 'rest\modules\api\v1\wallet\Module'
                        ],
                        'payment-system' => [
                            'class' => 'rest\modules\api\v1\paymentSystem\Module'
                        ],
                        'exchange-rates' => [
                            'class' => 'rest\modules\api\v1\exchangeRates\Module'
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
            'identityClass' => 'rest\modules\api\v1\authorization\entity\AuthUserEntity'
        ],
    ],
    'container' => [
        'singletons' => [
            // Repositories
            AuthUserRepositoryInterface::class => AuthUserRepository::class,

            // Services
            AuthUserServiceInterface::class => AuthUserService::class,
            SocialUserServiceInterface::class => SocialUserService::class,

            // Factories
            AuthUserFactoryInterface::class => AuthUserFactory::class,
        ]
    ],
    'params' => $params,
];

<?php
return [
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm'   => '@vendor/npm-asset',
    ],
    'vendorPath' => dirname(dirname(__DIR__)) . '/vendor',
    'components' => [
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'sendSms' => ['class' => 'common\components\SendSms'],
        'sendMail' => ['class' => 'common\components\SendMail'],
        'authManager' => [
            'class' => 'yii\rbac\DbManager',
        ],
    ],
];

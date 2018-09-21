<?php
return [
    'aliases'    => [
        '@bower' => '@vendor/bower-asset',
        '@npm'   => '@vendor/npm-asset',
    ],
    'vendorPath' => dirname(dirname(__DIR__)) . '/vendor',
    'components' => [
        'cache'       => [
            'class' => 'yii\caching\FileCache',
        ],
        'sendSms'     => ['class' => 'common\components\SendSms'],
        'sendMail'    => ['class' => 'common\components\SendMail'],
        'authManager' => [
            'class' => 'yii\rbac\DbManager',
        ],
        's3'          => [
            'class'         => \frostealth\yii2\aws\s3\Service::class,
            'credentials'   => [
                'key'    => 'AKIAJPBLFOYFCW3AFGHA',
                'secret' => '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',
            ],
            'region'        => 'us-east-1',
            'defaultBucket' => 'bigbizbucket',
            'defaultAcl'    => 'public-read'
        ],
        'i18n'        => [
            'translations' => [
                'app*' => [
                    'basePath' => '@messages',
                    'class'    => 'yii\i18n\PhpMessageSource',
                ],
            ],
        ],
    ],
];

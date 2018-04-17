<?php

return [
    /** Bid Module */
    [
        'class'      => 'yii\rest\UrlRule',
        'prefix'     => 'api/v1/',
        'controller' => [
            'bid' => 'api/v1/bid/bid',
        ],
        'patterns'   => [
            'POST'        => 'create',
            'PUT {id}'    => 'update',
            'DELETE {id}' => 'delete',
            'GET list'    => 'list',
            'GET detail'  => 'detail',
        ],
    ],
    /** User Module */
    [
        'class'      => 'yii\rest\UrlRule',
        'prefix'     => 'api/v1/',
        'controller' => [
            'user/profile' => 'api/v1/user/user-profile',
        ],
        'patterns'   => [
            'PUT'                 => 'update',
            'GET'                 => 'get-profile',
            'PUT update-password' => 'update-password',
        ],
    ],
    [
        'class'      => 'yii\rest\UrlRule',
        'prefix'     => 'api/v1/',
        'controller' => [
            'user/notifications' => 'api/v1/user/user-notifications',
        ],
        'patterns'   => [
            'GET list'    => 'list',
            'DELETE {id}' => 'delete'
        ],
    ],
    /** Reserve Module */
    [
        'class'      => 'yii\rest\UrlRule',
        'prefix'     => 'api/v1/',
        'controller' => [
            'reserve' => 'api/v1/reserve/reserve',
        ],
        'patterns'   => [
            'GET'      => 'list',
            'PUT {id}' => 'update',
            'POST'     => 'create',
        ],
    ],
    /** Social Authorization */
    [
        'class'      => 'yii\rest\UrlRule',
        'prefix'     => 'api/v1/',
        'controller' => [
            'social' => 'api/v1/authorization/social',
        ],
        'patterns'   => [
            'POST vk-register'         => 'vk-register',
            'POST vk-login'            => 'vk-login',
            'POST gmail-authorize'     => 'gmail-authorize',
            'POST fb-register'         => 'fb-register',
            'POST fb-login'            => 'fb-login',
        ],
    ],
    /** Authorization */
    [
        'class'      => 'yii\rest\UrlRule',
        'prefix'     => 'api/v1/',
        'controller' => [
            'authorization' => 'api/v1/authorization/authorization',
        ],
        'patterns'   => [
            'POST register'                  => 'register',
            'POST login'                     => 'login',
            'POST login-guest'               => 'login-guest',
            'POST generate-new-access-token' => 'generate-new-access-token',
            'POST password-recovery'     => 'password-recovery',
            'POST send-recovery-code'    => 'send-recovery-code',
            'POST verification-profile'  => 'verification-profile',
            'GET  logout'                => 'logout',
        ],
    ],
    /** Review */
    [
        'class'      => 'yii\rest\UrlRule',
        'prefix'     => 'api/v1/',
        'controller' => [
            'review' => 'api/v1/review/review',
        ],
        'patterns'   => [
            'POST'        => 'create',
            'PUT {id}'    => 'update',
            'GET list'    => 'list',
            'DELETE {id}' => 'delete',
        ],
    ],
    /** Wallet */
    [
        'class'      => 'yii\rest\UrlRule',
        'prefix'     => 'api/v1/',
        'controller' => [
            'wallet' => 'api/v1/wallet/wallet',
        ],
        'patterns'   => [
            'POST'        => 'create',
            'PUT {id}'    => 'update',
            'GET list'    => 'list',
            'DELETE {id}' => 'delete',
            'GET'         => 'list'
        ],
    ],
];
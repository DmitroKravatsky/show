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
<<<<<<< HEAD
            'POST vk-register'         => 'vk-register',
            'POST vk-login'            => 'vk-login',
            'POST gmail-authorize'     => 'gmail-authorize',
            'POST fb-register'         => 'fb-register',
            'POST fb-login'            => 'fb-login',
=======
            'POST vk-register'       => 'vk-register',
            'POST vk-login'          => 'vk-login',
            'POST gmail-register'    => 'gmail-register',
            'POST gmail-login'       => 'gmail-login',
            'POST fb-authorize'      => 'fb-authorize',
>>>>>>> 2a3df51abbd3c454e76ff49a11b52449f82b7de0
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
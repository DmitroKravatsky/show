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
            'user/user-profile' => 'api/v1/user/user-profile',
        ],
        'patterns'   => [
            'PUT {id}' => 'update',
            'GET {id}' => 'get-profile'
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
        ],
    ],
    /** Authorization Module */
    [
        'class'      => 'yii\rest\UrlRule',
        'prefix'     => 'api/v1/',
        'controller' => [
            'social' => 'api/v1/authorization/social',
        ],
        'patterns'   => [
            'POST vk-register' => 'vk-register',
        ],
    ],
];
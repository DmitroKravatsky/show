<?php

return [
    /** Bid Module */
    [
        'class' => 'yii\rest\UrlRule',
        'prefix' => 'api/v1/',
        'controller' => [
            'bid' => 'api/v1/bid/bid',
        ],
        'patterns' => [
            'POST' => 'create',
            'PUT {id}' => 'update',
            'DELETE {id}' => 'delete',
            'GET list' => 'list'
        ],
    ],
];
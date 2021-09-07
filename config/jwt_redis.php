<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Limit Token
    |--------------------------------------------------------------------------
    | Limit the number of tokens stored in redis
    |
    */
    'limit_token' => 5,

    /*
    |--------------------------------------------------------------------------
    | Key Hash
    |--------------------------------------------------------------------------
    | Hashed by algorithm to identify session
    |
    */
    'key_payload_hash' => [
        'imei',
        'user_id'
    ],

    /*
    |--------------------------------------------------------------------------
    | Except Route
    |--------------------------------------------------------------------------
    | Except route affected by middleware Http\Middleware\JwtRedisMiddleware
    |
    */
    'route_except' => [
        'api/login'
    ],

    /*
    |--------------------------------------------------------------------------
    | Push Middleware To Group
    |--------------------------------------------------------------------------
    | Push middleware Http\Middleware\JwtRedisMiddleware to Group 
    |
    */
    'group' => 'api',
];
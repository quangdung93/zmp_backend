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
    | The keys in the payload are used to hash session_id
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
        'api/login',
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
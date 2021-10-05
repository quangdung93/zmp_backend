<?php

namespace App\Services;

use GuzzleHttp\Client;

class ZaloService
{
    public function getZaloProfile($accessToken){
        $client = new Client();
        $response = $client->get('https://graph.zalo.me/v2.0/me', [
            'query' => [
                'access_token'  => $accessToken,
                'fields' => 'id,name,birthday,email,picture'
            ]
        ]);

        $dataResponse = json_decode($response->getBody(), true);

        return $dataResponse;
    }

    public function sendMessage($user_id, $message){
        $client = new Client();
        $response = $client->post('https://openapi.zalo.me/v2.0/oa/message', [
            'form_params' => [
                'recipient' => [
                    'user_id' => $user_id,
                ],
                'message' => [
                    'text' => $message
                ]
            ],[
                'Content-Type' => 'application/json',
                'access_token' => env('OA_TOKEN')
            ]
        ]);

        $dataResponse = json_decode($response->getBody(), true);
    }
}

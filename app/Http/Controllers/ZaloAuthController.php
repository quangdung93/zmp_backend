<?php

namespace App\Http\Controllers;

use App\ZaloUser;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Facades\JWTFactory;
use Illuminate\Support\Facades\Config;

class ZaloAuthController extends Controller
{
    public function __construct() {

    }

    public function loginZalo(Request $request){
        $accessToken = $request->accessToken;
        if (!$accessToken) {
			return response()->json([
                'error' => -1,
                'message' => 'Invalid access token'
            ]);
		}

        $profileZalo = $this->getZaloProfile($accessToken);
        if($profileZalo){
            $dataZalo = [
                'name' => $profileZalo['name'],
                'birthday' => $profileZalo['birthday'],
                'avatar' => $profileZalo['picture']['data']['url'],
            ];

            $user = ZaloUser::updateOrCreate(['id' => $profileZalo['id']], $dataZalo);
            if($user){
                $payload = JWTFactory::sub('token')->data(['id' => $user->id])->make();
                $token = JWTAuth::encode($payload)->get();
                return response()->json([
                    'access_token' => $token,
                    'token_type' => 'bearer',
                    'expires_in' => auth()->factory()->getTTL() * 60
                ]);
            }
        }
    }

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

    public function userProfile() {
        return response()->json([
            'error' => 0,
            'message' => 'Success!',
            'data' => request()->get('user')
        ]);
    }
}

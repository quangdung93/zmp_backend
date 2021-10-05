<?php

namespace App\Http\Controllers;

use App\Services\ZaloService;
use App\ZaloUser;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Facades\JWTFactory;

class ZaloAuthController extends Controller
{
    protected $zaloService;

    public function __construct(ZaloService $zaloService) {
        $this->zaloService = $zaloService;
    }

    public function loginZalo(Request $request){
        $accessToken = $request->accessToken;
        if (!$accessToken) {
			return response()->json([
                'error' => -1,
                'message' => 'Invalid access token'
            ]);
		}

        $profileZalo = $this->zaloService->getZaloProfile($accessToken);
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

    public function userProfile() {
        return response()->json([
            'error' => 0,
            'message' => 'Success!',
            'data' => request()->get('user')
        ]);
    }

    public function webhook(Request $request){
        $payload = $request->body;
        switch ($payload['event_name']) {
            case 'follow':
                $this->processFollowEvent($payload);
                break;

            case 'unfollow':
                $this->processUnfollowEvent($payload);
                break;

            default:
                break;
        }
    }

    private function processFollowEvent($payload){
        $followerId = $payload['follower']['id'];
        $user_id_by_app = $payload['user_id_by_app'];
        ZaloUser::where('id', $user_id_by_app)->update(['follow_id' => $followerId, 'is_follow' => 1]);
    }

    private function processUnfollowEvent($payload){
        $followerId = $payload['follower']['id'];
        $user_id_by_app = $payload['user_id_by_app'];
        ZaloUser::where('id', $user_id_by_app)->update(['follow_id' => $followerId, 'is_follow' => 0                   ]);
    }

}

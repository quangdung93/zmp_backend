<?php

namespace App\Http\Middleware;

use App\ZaloUser;
use Closure;
use Illuminate\Http\Response;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\Auth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Http\Middleware\BaseMiddleware;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;

class ZaloAuthMiddleware extends BaseMiddleware
{

    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {        
        try {
            $token = JWTAuth::getToken();
            $payload = JWTAuth::getPayload($token)->get('data');
            $user = ZaloUser::findOrFail($payload->id);

            if (!$user) {
                return response()->json(['error' => 'Account is not exits']);
            }

            $request->merge(['user' => $user->toArray()]);

            return $next($request);

        } catch (JWTException $e) {
            if ($e instanceof TokenInvalidException) {
                return response()->json(['error' => __('messages.errors.auth.token.invalid')], Response::HTTP_UNAUTHORIZED);
            }
            if ($e instanceof TokenExpiredException) {
                return response()->json(['error' => __('messages.errors.auth.token.expired')], Response::HTTP_UNAUTHORIZED);
            }

            return response()->json(['error' => __('messages.errors.auth.token.internal_error')], Response::HTTP_UNAUTHORIZED);
        }
    }
}
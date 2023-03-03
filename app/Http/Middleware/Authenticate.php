<?php

namespace App\Http\Middleware;

use App\Models\Permission;
use Closure;
use Illuminate\Contracts\Auth\Factory as Auth;
use Illuminate\Support\Facades\DB;
use Tymon\JWTAuth\Facades\JWTAuth;

class Authenticate
{
    /**
     * The authentication guard factory instance.
     *
     * @var \Illuminate\Contracts\Auth\Factory
     */
    protected $auth;

    /**
     * Create a new middleware instance.
     *
     * @param  \Illuminate\Contracts\Auth\Factory  $auth
     * @return void
     */
    public function __construct(Auth $auth)
    {
        $this->auth = $auth;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $guard
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {
        try {

            $userToken =  JWTAuth::parseToken()->authenticate();
            if ($userToken->api_token !== (string)JWTAuth::getToken()) {
                return response()->json(['message' => 'Token is Invalid']);
            }
        } catch (\Exception $e) {
            if ($e instanceof \Tymon\JWTAuth\Exceptions\TokenInvalidException) {
                return response()->json(['message' => 'Token is Invalid']);
            } else if ($e instanceof \Tymon\JWTAuth\Exceptions\TokenExpiredException) {
                return response()->json(['message' => 'Token is Expired']);
            } else {
                return response()->json(['message' => 'Authorization Token not found']);
            }
        }

        $user = $this->auth->user();
        $route = $request->route();
        $per = Permission::where('name', data_get($route, '1.as'))->first();
        if (!$user->roles) throw new \Exception('You are not allowed');
        if (!$per)  throw new \Exception('You are not allowed');
        foreach ($user->roles as $role) {
            $role->pivot->role_id;
            if (DB::table('role_permissions')
                ->where('role_id', data_get($role, 'pivot.role_id'))
                ->where('permission_id', $per->id)
                ->exists()
            ) {

                return $next($request);
            } else {
                throw new \Exception('You are not allowed');
            }
        }
    }
}

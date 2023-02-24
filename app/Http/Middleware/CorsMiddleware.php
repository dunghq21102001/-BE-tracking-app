<?php

namespace App\Http\Middleware;

use Closure;

class CorsMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        // Pre-Middleware Action
        $headers = [
            'Access-Control-Allow-Origin' => '*',
            'Access-Control-Allow-Methods' => 'POST, GET, OPTIONS, PUT, DELETE',
            'Access-control-allow-Credentials' => 'true',
            'Access-Control-Allow-Headers' => '*'

        ];
        if ($request->isMethod('OPTIONS')) {
            return response()->json('ok', 200, $headers);
        }
        $response = $next($request);

        // Post-Middleware Action
        if (\method_exists($response, 'header')) {
            $response->headers->set('Access-Control-Allow-Origin', '*');
            $response->headers->set('Access-Control-Allow-Methods', 'POST, GET, OPTIONS, PUT, DELETE');
            $response->headers->set('Access-Control-Allow-Headers', 'Content-Type, Accept, Authorization, X-Requested-With', 'Origin', 'Application');
        }
        if($response instanceof \Illuminate\Http\Response){
            foreach($headers as $key => $value) {
                $response->header($key, $value);
            }
            return $response;
        }

        if($response instanceof \Symfony\Component\HttpFoundation\Response){
            foreach($headers as $key => $value) {
                $response->headers->set($key, $value);
            }
            return $response;
        }
        

        return $response;
    }
}

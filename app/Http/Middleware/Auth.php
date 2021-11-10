<?php

namespace App\Http\Middleware;

use Closure;

class Auth
{
    protected $auth;

    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @param string|null $guard
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $wp_signature = $request->header('x-wc-webhook-signature');
        $payload = $request->getContent();
        $get_hmac = base64_encode(hash_hmac('sha256', $payload, env('APP_KEY'), true));

        if ($wp_signature != $get_hmac) {
            return response(['Invalid key'], 401);
        }

        return $next($request);
    }
}

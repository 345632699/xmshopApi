<?php

namespace App\Http\Middleware;

use Closure;
use Overtrue\Socialite\User as SocialiteUser;


class Scope
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
        $user = [];
        $user = new SocialiteUser([
            'id' => array_get($user, 'openid'),
            'name' => array_get($user, 'nickname'),
            'nickname' => array_get($user, 'nickname'),
            'avatar' => array_get($user, 'headimgurl'),
            'email' => null,
            'original' => [],
            'provider' => 'WeChat',
        ]);
        session(['wechat.oauth_user.default' => []]);
        return $next($request);
    }
}

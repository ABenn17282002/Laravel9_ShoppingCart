<?php

namespace App\Http\Middleware;

use App\Providers\RouteServiceProvider;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RedirectIfAuthenticated
{

    // config/auth側のGuard定義
    private const GUARD_USER = 'users';
    private const GUARD_OWNER = 'owners';
    private const GUARD_ADMIN = 'admin';

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @param  string|null  ...$guards
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next, ...$guards)
    {
        // 認証がUSERであり受信リクエストがuserルートの場合:Homeへリダイレクト
        if(Auth::guard(self::GUARD_USER)->check() && $request->routeIs('user.*')){
            return redirect(RouteServiceProvider::HOME);
          }

          // 認証がownerであり受信リクエストがownerルートの場合:OWNER_HOMEへリダイレクト
          if(Auth::guard(self::GUARD_OWNER)->check() && $request->routeIs('owner.*')){
            return redirect(RouteServiceProvider::OWNER_HOME);
          }

          // 認証がadminであり受信リクエストがadminルートの場合:ADMIN_HOMEへリダイレクト
          if(Auth::guard(self::GUARD_ADMIN)->check() && $request->routeIs('admin.*')){
            return redirect(RouteServiceProvider::ADMIN_HOME);
          }

        return $next($request);
    }
}

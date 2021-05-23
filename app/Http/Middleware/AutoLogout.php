<?php

namespace App\Http\Middleware;

use Closure;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class AutoLogout
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
        $user = Auth::user();

        if(Carbon::parse($user->updated_at, "Asia/Tashkent")->diffInMinutes(Carbon::now()) < 10)
            $user->updated_at = Carbon::now();    
        else
            $user->api_token = null;
        
        $user->save();
        return $next($request);
    }
}

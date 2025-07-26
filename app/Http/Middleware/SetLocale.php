<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;

class SetLocale
{
    public function handle(Request $request, Closure $next)
    {
        if (auth()->check()) {
            App::setLocale(auth()->user()->locale ?? config('app.locale'));
        } else {
            App::setLocale(session('locale', config('app.locale')));
        }

        return $next($request);
    }

}
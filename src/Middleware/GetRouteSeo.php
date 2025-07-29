<?php

namespace Manta\FluxCMS\Middleware;

use Manta\FluxCMS\Models\Routeseo;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Symfony\Component\HttpFoundation\Response;

class GetRouteSeo
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $routeName = Route::currentRouteName();
        $route = Routeseo::where(['route' => $routeName, 'locale' => app()->getLocale()])->first();
        if ($route == null) {
            $default = Routeseo::where(['route' => $routeName, 'locale' => getLocaleManta()])->first();
            if (!$default) {
                $route = RouteSeo::Create([
                    'locale' => getLocaleManta(),
                    'route' => Route::currentRouteName(),
                    'seo_title' => env('APP_NAME'),
                ]);
            } else
            if (getLocaleManta() != app()->getLocale()) {

                $route = RouteSeo::Create([
                    'pid' => $default->id ?? null,
                    'locale' => app()->getLocale(),
                    'route' => Route::currentRouteName(),
                    'seo_title' => env('APP_NAME'),
                ]);
            }
        }

        view()->share('routeseo', translate($route)['result']);

        return $next($request);
    }
}

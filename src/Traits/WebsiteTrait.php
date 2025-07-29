<?php

namespace Manta\Traits;

use Manta\FluxCMS\Models\Routeseo;
use Illuminate\Support\Facades\Route;

trait WebsiteTrait
{

    public $seo_title;
    public $seo_description;

    public $route_image;
    public ?int $route_id = null;
    public $header_image;
    public $header_image_500;
    public $header_image_800;

    public function getRouteSeo()
    {
        $routeName = Route::currentRouteName();

        $route = Routeseo::where(['route' => $routeName, 'locale' => app()->getLocale()])->first();
        if ($route == null) {
            $default = Routeseo::where(['route' => $routeName, 'locale' => getLocaleManta()])->first();

            if (!$default) {
                $default = RouteSeo::Create([
                    'locale' => getLocaleManta(),
                    'route' => Route::currentRouteName(),
                    'title' => env('APP_NAME'),
                    'seo_title' => env('APP_NAME'),
                ]);
            } else
            if (getLocaleManta() != app()->getLocale()) {

                $route = RouteSeo::Create([
                    'pid' => $default->id ?? null,
                    'locale' => app()->getLocale(),
                    'route' => Route::currentRouteName(),
                    'title' => env('APP_NAME'),
                    'seo_title' => env('APP_NAME'),
                ]);
            }
        } else {
            $default = Routeseo::where(['route' => $routeName, 'locale' => getLocaleManta()])->first();
        }
        if ($default) {
            $this->route_image = $default->image;
            if (auth('staff')->user()) {
                $this->route_id = (int)$default->id;
            }
        }
        $this->seo_title = $route->seo_title;
        $this->seo_description = $route->seo_description;

        if ($this->route_image) {
            $this->header_image = $this->route_image->getImage()['src'];
        }
    }
}

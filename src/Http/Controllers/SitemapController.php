<?php

namespace Manta\FluxCMS\Http\Controllers;

use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Config;
use Manta\FluxCMS\Models\MantaRoute;
use Manta\FluxCMS\Models\Routeseo;

class SitemapController extends Controller
{
    /**
     * Generate and return the sitemap.xml for public pages.
     */
    public function index(): Response
    {
        $cacheKey = 'manta_cms_sitemap';
        $cacheEnabled = config('sitemap.cache.enabled', true);
        $cacheTtl = config('sitemap.cache.ttl', 3600);

        if ($cacheEnabled && Cache::has($cacheKey)) {
            $sitemap = Cache::get($cacheKey);
        } else {
            $sitemap = $this->generateSitemap();

            if ($cacheEnabled) {
                Cache::put($cacheKey, $sitemap, $cacheTtl);
            }
        }

        return response($sitemap, 200, [
            'Content-Type' => 'application/xml',
            'Cache-Control' => 'public, max-age=' . $cacheTtl,
        ]);
    }

    /**
     * Generate the complete sitemap.
     */
    private function generateSitemap(): string
    {
        $urls = collect();

        // Add static URLs from configuration
        $staticUrls = config('sitemap.urls', []);
        foreach ($staticUrls as $urlConfig) {
            $urls->push([
                'url' => $urlConfig['url'],
                'lastmod' => now(),
                'changefreq' => $urlConfig['changefreq'] ?? 'weekly',
                'priority' => $urlConfig['priority'] ?? '0.8',
            ]);
        }

        // Add dynamic routes if enabled
        if (config('sitemap.dynamic_routes.enabled', true)) {
            $dynamicUrls = $this->getDynamicRoutes();
            $urls = $urls->merge($dynamicUrls);
        }

        return $this->generateSitemapXml($urls);
    }

    /**
     * Get dynamic routes from the database.
     */
    private function getDynamicRoutes(): array
    {
        $excludePrefixes = config('sitemap.dynamic_routes.exclude_prefixes', ['cms', 'admin', 'staff', 'account']);
        $excludePatterns = config('sitemap.dynamic_routes.exclude_patterns', []);
        $defaultChangefreq = config('sitemap.dynamic_routes.default_changefreq', 'weekly');
        $defaultPriority = config('sitemap.dynamic_routes.default_priority', '0.8');

        // Get all active public routes (not requiring authentication)
        $publicRoutes = MantaRoute::active()
            ->get()
            ->filter(function ($route) use ($excludePrefixes, $excludePatterns) {
                // Check if prefix starts with any excluded prefix
                foreach ($excludePrefixes as $excludePrefix) {
                    if (!empty($route->prefix) && str_starts_with($route->prefix, $excludePrefix)) {
                        return false;
                    }
                }
                
                return $this->isPublicRoute($route, $excludePatterns);
            });

        // Get SEO data for these routes
        $seoRoutes = Routeseo::whereIn('route', $publicRoutes->pluck('uri'))
            ->get()
            ->keyBy('route');

        $dynamicUrls = [];
        foreach ($publicRoutes as $route) {
            $seoData = $seoRoutes->get($route->uri);

            $dynamicUrls[] = [
                'url' => '/' . ltrim($route->uri, '/'),
                'lastmod' => $route->updated_at ?? $route->created_at ?? now(),
                'changefreq' => $seoData->data['sitemap_changefreq'] ?? $defaultChangefreq,
                'priority' => $seoData->data['sitemap_priority'] ?? $defaultPriority,
            ];
        }

        return $dynamicUrls;
    }

    /**
     * Check if a route is publicly accessible (doesn't require authentication).
     */
    private function isPublicRoute(MantaRoute $route, array $excludePatterns = []): bool
    {
        $uri = $route->uri;

        // Exclude routes that match specific patterns
        foreach ($excludePatterns as $pattern) {
            if (fnmatch($pattern, $uri)) {
                return false;
            }
        }

        // Exclude routes that typically require authentication or are not SEO-friendly
        $authPatterns = [
            '*/dashboard*',
            '*/profile*',
            '*/settings*',
            '*/account*',
            '*/login*',
            '*/register*',
            '*/logout*',
            '*/password*',
            '*/verification*',
            '*/verify*',
            '*/reset*',
            '*/confirm*',
            '*/auth/*',
            '*/user/*',
            '*/member/*',
            '*/private/*',
            '*/secure/*',
            '*/cms/*',
            'cms/*',
            'cms*',
            '*/filemanager/*',
            'filemanager/*',
            'filemanager*',
            '*/flux/*',
            'flux/*',
            'flux*',
            '*/livewire/*',
            'livewire/*',
            'livewire*',
            // Development/debug routes
            'lemmings',
            'up',
            '*test*',
            '*debug*',
            '*dev*',
            // Routes with parameters (not SEO-friendly)
            '*{*}*',
            '*/storage/*',
            'storage/*',
            // Dutch authentication terms
            '*aanmelden*',
            '*inloggen*',
            '*uitloggen*',
            '*wachtwoord*',
            '*profiel*',
            '*instellingen*',
        ];

        foreach ($authPatterns as $pattern) {
            if (fnmatch($pattern, $uri)) {
                return false;
            }
        }

        // Check route name for authentication indicators
        if ($route->name) {
            $authNamePatterns = [
                '*login*',
                '*register*',
                '*auth*',
                '*dashboard*',
                '*profile*',
                '*settings*',
                '*account*',
                '*admin*',
                '*cms*',
                '*staff*',
                '*member*',
                '*user*',
                '*private*',
                '*secure*',
                '*filemanager*',
                '*flux*',
                '*livewire*',
                '*test*',
                '*debug*',
                '*dev*',
                '*storage*',
            ];

            foreach ($authNamePatterns as $pattern) {
                if (fnmatch($pattern, strtolower($route->name))) {
                    return false;
                }
            }
        }

        return true;
    }

    /**
     * Generate the sitemap XML content.
     */
    private function generateSitemapXml($urls): string
    {
        $baseUrl = config('app.url');
        $xml = '<?xml version="1.0" encoding="UTF-8"?>' . PHP_EOL;
        $xml .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . PHP_EOL;

        foreach ($urls as $urlData) {
            $fullUrl = $baseUrl . rtrim($urlData['url'], '/');
            $xml .= $this->addUrlToSitemap(
                $fullUrl,
                $urlData['lastmod'],
                $urlData['changefreq'],
                $urlData['priority']
            );
        }

        $xml .= '</urlset>';

        return $xml;
    }

    /**
     * Add a URL entry to the sitemap XML.
     */
    private function addUrlToSitemap(string $url, $lastmod, string $changefreq = 'weekly', string $priority = '0.8'): string
    {
        $xml = '  <url>' . PHP_EOL;
        $xml .= '    <loc>' . htmlspecialchars($url) . '</loc>' . PHP_EOL;
        $xml .= '    <lastmod>' . $lastmod->format('Y-m-d\TH:i:s\Z') . '</lastmod>' . PHP_EOL;
        $xml .= '    <changefreq>' . $changefreq . '</changefreq>' . PHP_EOL;
        $xml .= '    <priority>' . $priority . '</priority>' . PHP_EOL;
        $xml .= '  </url>' . PHP_EOL;

        return $xml;
    }
}

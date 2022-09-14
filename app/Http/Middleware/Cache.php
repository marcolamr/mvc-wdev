<?php

namespace App\Http\Middleware;

use App\Http\Request;
use App\Http\Response;
use App\Utils\Cache\File as CacheFile;
use Closure;

class Cache
{
    /**
     * Executa o middleware
     *
     * @param Request $request
     * @param Closure $next
     * @return Response
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!$this->isCacheable($request)) return $next($request);

        $hash = $this->getHash($request);

        return CacheFile::getCache($hash, getenv("CACHE_TIME_IN_SECONDS"), function () use ($request, $next) {
            return $next($request);
        });
    }

    private function getHash(Request $request): string
    {
        $uri = $request->getRouter()->getUri();

        $queryParams = $request->getQueryParams();
        $uri .= !empty($queryParams) ? "?" . http_build_query($queryParams) : "";

        return rtrim("route-" . preg_replace("/[^0-9a-zA-Z]/", "-", ltrim($uri, "/")), "-");
    }

    private function isCacheable(Request $request)
    {
        if (getenv("CACHE_TIME_IN_SECONDS") <= 0) {
            return false;
        }

        if ($request->getHttpMethod() != "GET") {
            return false;
        }

        $headers = $request->getHeaders();
        if (isset($headers["Cache-Control"]) && $headers["Cache-Control"] == "no-cache") {
            return false;
        }

        return true;
    }
}

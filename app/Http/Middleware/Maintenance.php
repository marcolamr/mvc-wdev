<?php

namespace App\Http\Middleware;

use App\Http\Request;
use App\Http\Response;
use Closure;
use Exception;

class Maintenance
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
        if (getenv("MAINTENANCE") == "true") {
            throw new Exception("Página em manutenção, tente novamente mais tarde", 200);
        }

        return $next($request);
    }
}

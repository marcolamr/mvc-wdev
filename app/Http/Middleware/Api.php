<?php

namespace App\Http\Middleware;

use App\Http\Request;
use App\Http\Response;
use Closure;
use Exception;

class Api
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
        $request->getRouter()->setContentType("application/json");

        return $next($request);
    }
}

<?php

namespace App\Http\Middleware;

use App\Http\Request;
use App\Http\Response;
use App\Session\Admin\Login as SessionAdminLogin;
use Closure;
use Exception;

class RequireAdminLogin
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
        if (!SessionAdminLogin::isLogged()) {
            $request->getRouter()->redirect("/admin/login");
        }

        return $next($request);
    }
}

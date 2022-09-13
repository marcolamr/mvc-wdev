<?php

namespace App\Http\Middleware;

use App\Http\Request;
use App\Http\Response;
use App\Model\Entity\User;
use Closure;
use Exception;

class UserBasicAuth
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
        $this->basicAuth($request);

        return $next($request);
    }

    private function basicAuth(Request $request): bool
    {
        if ($user = $this->getBasicAuthUser()) {
            $request->user = $user;
            return true;
        }

        throw new Exception("Usuário ou senha inválidos", 403);
    }

    private function getBasicAuthUser()
    {
        if (!isset($_SERVER["PHP_AUTH_USER"]) || !isset($_SERVER["PHP_AUTH_PW"])) {
            return false;
        }

        $user = User::getUserByEmail($_SERVER["PHP_AUTH_USER"]);

        if (!$user instanceof User) {
            return false;
        }

        return password_verify($_SERVER["PHP_AUTH_PW"], $user->senha) ? $user : false;
    }
}

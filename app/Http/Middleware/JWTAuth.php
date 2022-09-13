<?php

namespace App\Http\Middleware;

use App\Http\Request;
use App\Http\Response;
use App\Model\Entity\User;
use Closure;
use Exception;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class JWTAuth
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
        $this->auth($request);

        return $next($request);
    }

    private function auth(Request $request): bool
    {
        if ($user = $this->getJWTAuthUser($request)) {
            $request->user = $user;
            return true;
        }

        throw new Exception("Acesso negado", 403);
    }

    private function getJWTAuthUser(Request $request)
    {
        $headers = $request->getHeaders();
        $jwt = isset($headers["Authorization"]) ? str_replace("Bearer ", "", $headers["Authorization"]) : "";

        try {
            $decoded = (array)JWT::decode($jwt, new Key(getenv("JWT_KEY"), "HS256"));
        } catch (\Exception $e) {
            throw new Exception("Token inválido", 403);
        }

        $email = $decoded["email"] ?? "";

        $user = User::getUserByEmail($email);

        return $user instanceof User ? $user : false;
    }
}

<?php

namespace App\Controller\Api;

use App\Http\Request;
use App\Model\Entity\User as EntityUser;
use DateInterval;
use DateTime;
use Exception;
use Firebase\JWT\JWT;

class Auth extends Api
{
    public static function generateToken(Request $request): array
    {
        $postVars = $request->getPostVars();

        if (!isset($postVars["email"]) || !isset($postVars["senha"])) {
            throw new Exception("Os campos 'email' e 'senha' são obrigatórios", 400);
        }

        $user = EntityUser::getUserByEmail($postVars["email"]);
        if (!$user instanceof EntityUser) {
            throw new Exception("Usuário ou senha inválidos", 400);
        }

        if (!password_verify($postVars["senha"], $user->senha)) {
            throw new Exception("Usuário ou senha inválidos", 400);
        }

        $payload = [
            "email" => $user->email,
            "exp" => (new DateTime())->add(new DateInterval("PT1H"))->getTimestamp()
        ];

        return [
            "token" => JWT::encode($payload, getenv("JWT_KEY"), "HS256")
        ];
    }
}

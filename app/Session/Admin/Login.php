<?php

namespace App\Session\Admin;

use App\Model\Entity\User;

class Login
{
    /**
     * Inicia a sessão
     *
     * @return void
     */
    private static function init()
    {
        if (session_status() != PHP_SESSION_ACTIVE) {
            session_start();
        }
    }

    /**
     * Cria o login do usuário
     *
     * @param User $user
     * @return boolean
     */
    public static function login(User $user): bool
    {
        self::init();

        $_SESSION["admin"]["usuario"] = [
            "id" => $user->id,
            "nome" => $user->nome,
            "email" => $user->email,
        ];

        return true;
    }

    /**
     * Verifica se usuário está logado
     *
     * @return boolean
     */
    public static function isLogged(): bool
    {
        self::init();

        return isset($_SESSION["admin"]["usuario"]["id"]);
    }

    /**
     * Desloga o usuário
     *
     * @return boolean
     */
    public static function logout(): bool
    {
        self::init();

        unset($_SESSION["admin"]["usuario"]);

        return true;
    }
}

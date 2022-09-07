<?php

namespace App\Controller\Admin;

use App\Http\Request;
use App\Model\Entity\User;
use App\Utils\View;
use App\Session\Admin\Login as SessionAdminLogin;

class Login extends Page
{
    /**
     * Retorna a renderização da página de login
     *
     * @param Request $request
     * @param null|string $errorMessage
     * @return string
     */
    public static function getLogin(Request $request, ?string $errorMessage = null): string
    {
        $status = !is_null($errorMessage) ? View::render("admin/login/status", [
            "mensagem" => $errorMessage
        ]) : "";

        $content = View::render("admin/login", [
            "status" => $status
        ]);

        return parent::getPage("Login > WDEV", $content);
    }

    public static function setLogin(Request $request)
    {
        $postVars = $request->getPostVars();
        $email = $postVars["email"] ?? "";
        $senha = $postVars["password"] ?? "";

        $user = User::getUserByEmail($email);
        if (!$user instanceof User) {
            return self::getLogin($request, "E-mail e/ou senha inválidos");
        }

        if (!password_verify($senha, $user->senha)) {
            return self::getLogin($request, "E-mail e/ou senha inválidos");
        }

        SessionAdminLogin::login($user);

        $request->getRouter()->redirect("/admin");
    }

    /**
     * Desloga o usuário
     *
     * @param Request $request
     * @return void
     */
    public static function setLogout(Request $request): void
    {
        SessionAdminLogin::logout();

        $request->getRouter()->redirect("/admin/login");
    }
}

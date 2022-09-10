<?php

namespace App\Controller\Admin;

use App\Http\Request;
use App\Utils\View;
use App\Model\Entity\User as EntityUser;
use WilliamCosta\DatabaseManager\Pagination;

class User extends Page
{
    /**
     * Renderiza a listagem de usuários
     *
     * @param Request $request
     * @return string
     */
    public static function getUsers(Request $request): string
    {
        $content = View::render("admin/modules/users/index", [
            "items" => self::getUserItems($request, $pagination),
            "pagination" => parent::getPagination($request, $pagination),
            "status" => self::getStatus($request)
        ]);

        return parent::getPanel("Usuários > WDEV", $content, "users");
    }

    public static function getNewUser(Request $request): string
    {
        $content = View::render("admin/modules/users/form", [
            "title" => "Cadastrar usuário",
            "nome" => "",
            "email" => "",
            "status" => self::getStatus($request)
        ]);

        return parent::getPanel("Cadastrar usuário > WDEV", $content, "users");
    }

    public static function setNewUser(Request $request): void
    {
        $postVars = $request->getPostVars();
        $email = $postVars["email"] ?? "";
        $nome = $postVars["nome"] ?? "";
        $senha = $postVars["senha"] ?? "";

        $user = EntityUser::getUserByEmail($email);
        if ($user instanceof EntityUser) {
            $request->getRouter()->redirect("/admin/users/new?status=duplicated");
        }

        $user = new EntityUser();
        $user->nome = $nome;
        $user->email = $email;
        $user->senha = password_hash($senha, PASSWORD_DEFAULT);
        $user->cadastrar();

        $request->getRouter()->redirect("/admin/users/{$user->id}/edit?status=created");
    }

    public static function getEditUser(Request $request, int $id): string
    {
        $user = EntityUser::getUserById($id);

        if (!$user instanceof EntityUser) {
            $request->getRouter()->redirect("/admin/users");
        }

        $content = View::render("admin/modules/users/form", [
            "title" => "Editar usuário",
            "nome" => $user->nome,
            "email" => $user->email,
            "status" => self::getStatus($request)
        ]);

        return parent::getPanel("Editar usuário > WDEV", $content, "users");
    }

    public static function setEditUser(Request $request, int $id)
    {
        $user = EntityUser::getUserById($id);

        if (!$user instanceof EntityUser) {
            $request->getRouter()->redirect("/admin/users");
        }

        $postVars = $request->getPostVars();

        $email = $postVars["email"] ?? "";
        $nome = $postVars["nome"] ?? "";
        $senha = $postVars["senha"] ?? "";

        $userEmail = EntityUser::getUserByEmail($email);
        if ($userEmail instanceof EntityUser && $userEmail->id != $id) {
            $request->getRouter()->redirect("/admin/users/{$id}/edit?status=duplicated");
        }

        $user->nome = $nome;
        $user->email = $email;
        $user->senha = password_hash($senha, PASSWORD_DEFAULT);
        $user->atualizar();

        $request->getRouter()->redirect("/admin/users/{$user->id}/edit?status=updated");
    }

    public static function getDeleteUser(Request $request, int $id): string
    {
        $user = EntityUser::getUserById($id);

        if (!$user instanceof EntityUser) {
            $request->getRouter()->redirect("/admin/users");
        }

        $content = View::render("admin/modules/users/delete", [
            "nome" => $user->nome,
            "email" => $user->email
        ]);

        return parent::getPanel("Excluir usuário > WDEV", $content, "users");
    }

    public static function setDeleteUser(Request $request, int $id)
    {
        $user = EntityUser::getUserById($id);

        if (!$user instanceof EntityUser) {
            $request->getRouter()->redirect("/admin/users");
        }

        $user->excluir();

        $request->getRouter()->redirect("/admin/users?status=deleted");
    }

    /**
     * Retorna mensagem de status
     *
     * @param Request $request
     * @return string
     */
    private static function getStatus(Request $request): string
    {
        $queryParams = $request->getQueryParams();

        if (!isset($queryParams["status"])) return "";

        switch ($queryParams["status"]) {
            case "created":
                return Alert::getSuccess("Usuário criado com sucesso");
                break;
            case "updated":
                return Alert::getSuccess("Usuário atualizado com sucesso");
                break;
            case "deleted":
                return Alert::getSuccess("Usuário excluído com sucesso");
                break;
            case "duplicated":
                return Alert::getError("E-mail já existe");
                break;
        }
    }

    /**
     * Obtêm os itens de usuários
     *
     * @param Request $request
     * @param Pagination $pagination
     * @return string
     */
    private static function getUserItems(Request $request, &$pagination): string
    {
        $items = "";

        $total = EntityUser::getUsers(null, null, null, "COUNT(*) as qtd")->fetchObject()->qtd;

        $queryParams = $request->getQueryParams();
        $actualPage = $queryParams["page"] ?? 1;

        $pagination = new Pagination($total, $actualPage, 3);

        $results = EntityUser::getUsers(null, "id DESC", $pagination->getLimit());

        while ($user = $results->fetchObject(EntityUser::class)) {
            $items .= View::render("admin/modules/users/item", [
                "id" => $user->id,
                "nome" => $user->nome,
                "email" => $user->email
            ]);
        }

        return $items;
    }
}

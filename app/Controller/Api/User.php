<?php

namespace App\Controller\Api;

use App\Http\Request;
use App\Model\Entity\User as EntityUser;
use Exception;
use WilliamCosta\DatabaseManager\Pagination;

class User extends Api
{
    public static function getUsers($request): array
    {
        return [
            "usuários" => self::getUserItems($request, $pagination),
            "paginacao" => parent::getPagination($request, $pagination)
        ];
    }

    /**
     * Obtêm os itens de depoimentos
     *
     * @param Request $request
     * @param Pagination $pagination
     * @return array
     */
    private static function getUserItems(Request $request, &$pagination): array
    {
        $items = [];

        $total = EntityUser::getUsers(null, null, null, "COUNT(*) as qtd")->fetchObject()->qtd;

        $queryParams = $request->getQueryParams();
        $actualPage = $queryParams["page"] ?? 1;

        $pagination = new Pagination($total, $actualPage, 3);

        $results = EntityUser::getUsers(null, "id ASC", $pagination->getLimit());

        while ($user = $results->fetchObject(EntityUser::class)) {
            $items[] = [
                "id" => (int)$user->id,
                "nome" => $user->nome,
                "email" => $user->email
            ];
        }

        return $items;
    }

    public static function getUser(Request $request, $id): array
    {
        if (!is_numeric($id)) {
            throw new Exception("O id '{$id}' não é válido", 400);
        }

        $user = EntityUser::getUserById($id);

        if (!$user instanceof EntityUser) {
            throw new Exception("O usuário {$id} não foi encontrado", 404);
        }

        return [
            "id" => (int)$user->id,
            "nome" => $user->nome,
            "email" => $user->email
        ];
    }

    public static function setNewUser(Request $request)
    {
        $postVars = $request->getPostVars();

        if (!isset($postVars["nome"]) || !isset($postVars["email"]) || !isset($postVars["senha"])) {
            throw new Exception("Os campos 'nome', 'email' e 'senha' são obrigatórios", 400);
        }

        $userEmail = EntityUser::getUserByEmail($postVars["email"]);
        if ($userEmail instanceof EntityUser) {
            throw new Exception("O e-mail '{$postVars["email"]}' já está em uso", 400);
        }

        $user = new EntityUser();
        $user->nome = $postVars["nome"];
        $user->email = $postVars["email"];
        $user->senha = password_hash($postVars["senha"], PASSWORD_DEFAULT);
        $user->cadastrar();

        return [
            "id" => (int)$user->id,
            "nome" => $user->nome,
            "email" => $user->email
        ];
    }

    public static function setEditUser(Request $request, $id)
    {
        if (!is_numeric($id)) {
            throw new Exception("O id '{$id}' não é válido", 400);
        }

        $postVars = $request->getPostVars();

        if (!isset($postVars["nome"]) || !isset($postVars["email"]) || !isset($postVars["senha"])) {
            throw new Exception("Os campos 'nome', 'email' e 'senha' são obrigatórios", 400);
        }

        $user = EntityUser::getUserById($id);
        if (!$user instanceof EntityUser) {
            throw new Exception("O usuário {$id} não foi encontrado", 404);
        }

        $userEmail = EntityUser::getUserByEmail($postVars["email"]);
        if ($userEmail instanceof EntityUser && $userEmail->id != $user->id) {
            throw new Exception("O e-mail '{$postVars["email"]}' já está em uso", 400);
        }

        $user->nome = $postVars["nome"];
        $user->email = $postVars["email"];
        $user->senha = password_hash($postVars["senha"], PASSWORD_DEFAULT);
        $user->atualizar();

        return [
            "id" => (int)$user->id,
            "nome" => $user->nome,
            "email" => $user->email
        ];
    }

    public static function setDeleteUser(Request $request, $id)
    {
        if (!is_numeric($id)) {
            throw new Exception("O id '{$id}' não é válido", 400);
        }

        $user = EntityUser::getUserById($id);

        if (!$user instanceof EntityUser) {
            throw new Exception("O usuário {$id} não foi encontrado", 404);
        }

        if ($user->id == $request->user->id) {
            throw new Exception("Não é possível excluir o cadastro atualmente conectado", 400);
        }

        $user->excluir();

        return [
            "sucesso" => true
        ];
    }
}

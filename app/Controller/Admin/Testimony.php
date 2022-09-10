<?php

namespace App\Controller\Admin;

use App\Http\Request;
use App\Utils\View;
use App\Model\Entity\Testimony as EntityTestimony;
use WilliamCosta\DatabaseManager\Pagination;

class Testimony extends Page
{
    /**
     * Renderiza a listagem de depoimentos
     *
     * @param Request $request
     * @return string
     */
    public static function getTestimonies(Request $request): string
    {
        $content = View::render("admin/modules/testimonies/index", [
            "items" => self::getTestimonyItems($request, $pagination),
            "pagination" => parent::getPagination($request, $pagination),
            "status" => self::getStatus($request)
        ]);

        return parent::getPanel("Depoimentos > WDEV", $content, "testimonies");
    }

    public static function getNewTestimony(Request $request): string
    {
        $content = View::render("admin/modules/testimonies/form", [
            "title" => "Cadastrar depoimento",
            "nome" => "",
            "mensagem" => "",
            "status" => ""
        ]);

        return parent::getPanel("Cadastrar depoimento > WDEV", $content, "testimonies");
    }

    public static function setNewTestimony(Request $request): void
    {
        $postVars = $request->getPostVars();

        $testimony = new EntityTestimony();
        $testimony->nome = $postVars["nome"] ?? "";
        $testimony->mensagem = $postVars["mensagem"] ?? "";
        $testimony->cadastrar();

        $request->getRouter()->redirect("/admin/testimonies/{$testimony->id}/edit?status=created");
    }

    public static function getEditTestimony(Request $request, int $id): string
    {
        $testimony = EntityTestimony::getTestimonyById($id);

        if (!$testimony instanceof EntityTestimony) {
            $request->getRouter()->redirect("/admin/testimonies");
        }

        $content = View::render("admin/modules/testimonies/form", [
            "title" => "Editar depoimento",
            "nome" => $testimony->nome,
            "mensagem" => $testimony->mensagem,
            "status" => self::getStatus($request)
        ]);

        return parent::getPanel("Editar depoimento > WDEV", $content, "testimonies");
    }

    public static function setEditTestimony(Request $request, int $id)
    {
        $testimony = EntityTestimony::getTestimonyById($id);

        if (!$testimony instanceof EntityTestimony) {
            $request->getRouter()->redirect("/admin/testimonies");
        }

        $postVars = $request->getPostVars();

        $testimony->nome = $postVars["nome"] ?? $testimony->nome;
        $testimony->mensagem = $postVars["mensagem"] ?? $testimony->mensagem;
        $testimony->atualizar();

        $request->getRouter()->redirect("/admin/testimonies/{$testimony->id}/edit?status=updated");
    }

    public static function getDeleteTestimony(Request $request, int $id): string
    {
        $testimony = EntityTestimony::getTestimonyById($id);

        if (!$testimony instanceof EntityTestimony) {
            $request->getRouter()->redirect("/admin/testimonies");
        }

        $content = View::render("admin/modules/testimonies/delete", [
            "nome" => $testimony->nome,
            "mensagem" => $testimony->mensagem
        ]);

        return parent::getPanel("Excluir depoimento > WDEV", $content, "testimonies");
    }

    public static function setDeleteTestimony(Request $request, int $id)
    {
        $testimony = EntityTestimony::getTestimonyById($id);

        if (!$testimony instanceof EntityTestimony) {
            $request->getRouter()->redirect("/admin/testimonies");
        }

        $testimony->excluir();

        $request->getRouter()->redirect("/admin/testimonies?status=deleted");
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
                return Alert::getSuccess("Depoimento criado com sucesso");
                break;
            case "updated":
                return Alert::getSuccess("Depoimento atualizado com sucesso");
                break;
            case "deleted":
                return Alert::getSuccess("Depoimento excluído com sucesso");
                break;
        }
    }

    /**
     * Obtêm os itens de depoimentos
     *
     * @param Request $request
     * @param Pagination $pagination
     * @return string
     */
    private static function getTestimonyItems(Request $request, &$pagination): string
    {
        $items = "";

        $total = EntityTestimony::getTestimonies(null, null, null, "COUNT(*) as qtd")->fetchObject()->qtd;

        $queryParams = $request->getQueryParams();
        $actualPage = $queryParams["page"] ?? 1;

        $pagination = new Pagination($total, $actualPage, 3);

        $results = EntityTestimony::getTestimonies(null, "id DESC", $pagination->getLimit());

        while ($testimony = $results->fetchObject(EntityTestimony::class)) {
            $items .= View::render("admin/modules/testimonies/item", [
                "id" => $testimony->id,
                "nome" => $testimony->nome,
                "mensagem" => $testimony->mensagem,
                "data" => date("d/m/Y H:i:s", strtotime($testimony->data))
            ]);
        }

        return $items;
    }
}

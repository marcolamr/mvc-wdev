<?php

namespace App\Controller\Pages;

use App\Http\Request;
use App\Utils\View;
use App\Model\Entity\Testimony as EntityTestimony;
use WilliamCosta\DatabaseManager\Pagination;

class Testimony extends Page
{
    /**
     * @param Request $request
     * @return string
     */
    public static function getTestimonies(Request $request): string
    {
        $content = View::render("pages/testimonies", [
            "items" => self::getTestimonyItems($request, $pagination),
            "pagination" => parent::getPagination($request, $pagination)
        ]);

        return parent::getPage("DEPOIMENTOS > WDEV", $content);
    }

    /**
     * Cadastra um depoimento
     *
     * @param Request $request
     * @return string
     */
    public static function insertTestimony(Request $request): string
    {
        $postVars = $request->getPostVars();

        $testimony = new EntityTestimony();
        $testimony->nome = $postVars["nome"];
        $testimony->mensagem = $postVars["mensagem"];
        $testimony->cadastrar();

        return self::getTestimonies($request);
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
            $items .= View::render("pages/testimony/item", [
                "nome" => $testimony->nome,
                "mensagem" => $testimony->mensagem,
                "data" => date("d/m/Y H:i:s", strtotime($testimony->data))
            ]);
        }

        return $items;
    }
}

<?php

namespace App\Controller\Api;

use App\Http\Request;
use App\Model\Entity\Testimony as EntityTestimony;
use Exception;
use WilliamCosta\DatabaseManager\Pagination;

class Testimony extends Api
{
    public static function getTestimonies($request): array
    {
        return [
            "depoimentos" => self::getTestimonyItems($request, $pagination),
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
    private static function getTestimonyItems(Request $request, &$pagination): array
    {
        $items = [];

        $total = EntityTestimony::getTestimonies(null, null, null, "COUNT(*) as qtd")->fetchObject()->qtd;

        $queryParams = $request->getQueryParams();
        $actualPage = $queryParams["page"] ?? 1;

        $pagination = new Pagination($total, $actualPage, 3);

        $results = EntityTestimony::getTestimonies(null, "id DESC", $pagination->getLimit());

        while ($testimony = $results->fetchObject(EntityTestimony::class)) {
            $items[] = [
                "id" => (int)$testimony->id,
                "nome" => $testimony->nome,
                "mensagem" => $testimony->mensagem,
                "data" => $testimony->data
            ];
        }

        return $items;
    }

    public static function getTestimony(Request $request, $id): array
    {
        if (!is_numeric($id)) {
            throw new Exception("O id '{$id}' não é válido", 400);
        }

        $testimony = EntityTestimony::getTestimonyById($id);

        if (!$testimony instanceof EntityTestimony) {
            throw new Exception("O depoimento {$id} não foi encontrado", 404);
        }

        return [
            "id" => (int)$testimony->id,
            "nome" => $testimony->nome,
            "mensagem" => $testimony->mensagem,
            "data" => $testimony->data
        ];
    }
}
<?php

namespace App\Controller\Api;

use App\Http\Request;
use WilliamCosta\DatabaseManager\Pagination;

class Api
{
    public static function getDetails($request): array
    {
        return [
            "nome" => "API - WDEV",
            "versao" => "v1.0.0",
            "autor" => "William Costa",
            "email" => "canalwdev@gmail.com"
        ];
    }

    protected static function getPagination(Request $request, Pagination $pagination): array
    {
        $queryParams = $request->getQueryParams();
        $pages = $pagination->getPages();

        return [
            "paginaAtual" => isset($queryParams["page"]) ? (int)$queryParams["page"] : 1,
            "quantidadePaginas" => !empty($pages) ? count($pages) : 1
        ];
    }
}

<?php

namespace App\Controller\Pages;

use App\Http\Request;
use App\Utils\View;
use WilliamCosta\DatabaseManager\Pagination;

class Page
{
    /**
     * @param string $title
     * @param string $content
     * @return string
     */
    public static function getPage(string $title, string $content): string
    {
        return View::render("pages/page", [
            "title" => $title,
            "header" => self::getHeader(),
            "content" => $content,
            "footer" => self::getFooter()
        ]);
    }

    /**
     * Renderiza o layout de paginação
     *
     * @param Request $request
     * @param Pagination $pagination
     * @return string
     */
    public static function getPagination(Request $request, Pagination $pagination): string
    {
        $pages = $pagination->getPages();

        if (count($pages) <= 0) return "";

        $links = "";

        $url = $request->getRouter()->getCurrentUrl();

        $queryParams = $request->getQueryParams();

        foreach ($pages as $page) {
            $queryParams["page"] = $page["page"];

            $link = $url . "?" . http_build_query($queryParams);

            $links .= View::render("pages/pagination/link", [
                "page" => $page["page"],
                "link" => $link,
                "active" => $page["current"] ? "active" : ""
            ]);
        }

        return View::render("pages/pagination/box", [
            "links" => $links
        ]);
    }

    /**
     * Renderiza o topo da página
     *
     * @return string
     */
    private static function getHeader(): string
    {
        return View::render("pages/header");
    }

    /**
     * Renderiza o rodapé da página
     *
     * @return string
     */
    private static function getFooter(): string
    {
        return View::render("pages/footer");
    }
}

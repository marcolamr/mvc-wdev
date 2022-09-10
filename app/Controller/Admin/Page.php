<?php

namespace App\Controller\Admin;

use App\Http\Request;
use App\Utils\View;
use WilliamCosta\DatabaseManager\Pagination;

class Page
{
    /** @var array */
    private static $modules = [
        "home" => [
            "label" => "Home",
            "link" => URL . "/admin"
        ],
        "testimonies" => [
            "label" => "Depoimentos",
            "link" => URL . "/admin/testimonies"
        ],
        "users" => [
            "label" => "Usuários",
            "link" => URL . "/admin/users"
        ]
    ];

    /**
     * Retorna o conteúdo da estrutura genérica da página
     *
     * @param string $title
     * @param string $content
     * @return string
     */
    public static function getPage(string $title, string $content): string
    {
        return View::render("admin/page", [
            "title" => $title,
            "content" => $content
        ]);
    }

    /**
     * Renderiza a view do painel com conteúdos dinâmicos
     *
     * @param string $title
     * @param string $content
     * @param string $currentModule
     * @return string
     */
    public static function getPanel(string $title, string $content, string $currentModule): string
    {
        $contentPanel = View::render("admin/panel", [
            "menu" => self::getMenu($currentModule),
            "content" => $content
        ]);

        return self::getPage($title, $contentPanel);
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

            $links .= View::render("admin/pagination/link", [
                "page" => $page["page"],
                "link" => $link,
                "active" => $page["current"] ? "active" : ""
            ]);
        }

        return View::render("admin/pagination/box", [
            "links" => $links
        ]);
    }

    /**
     * Renderiza o menu do painel
     *
     * @param string $currentModule
     * @return string
     */
    private static function getMenu(string $currentModule): string
    {
        $links = "";

        foreach (self::$modules as $hash => $module) {
            $links .= View::render("admin/menu/link", [
                "label" => $module["label"],
                "link" => $module["link"],
                "current" => $hash == $currentModule ? "text-danger" : ""
            ]);
        }

        return View::render("admin/menu/box", [
            "links" => $links
        ]);
    }
}

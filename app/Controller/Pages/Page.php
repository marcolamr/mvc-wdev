<?php

namespace App\Controller\Pages;

use App\Utils\View;

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

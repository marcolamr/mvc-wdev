<?php

namespace App\Controller\Admin;

use App\Http\Request;
use App\Utils\View;

class Home extends Page
{
    /**
     * Renderiza a home do painel
     *
     * @param Request $request
     * @return string
     */
    public static function getHome(Request $request): string
    {
        $content = View::render("admin/modules/home/index", []);

        return parent::getPanel("Home Admin > WDEV", $content, "home");
    }
}

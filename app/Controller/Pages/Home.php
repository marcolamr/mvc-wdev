<?php

namespace App\Controller\Pages;

use App\Model\Entity\Organization;
use App\Utils\View;

class Home extends Page
{
    /**
     * @return string
     */
    public static function getHome(): string
    {
        $organization = new Organization();

        $content = View::render("pages/home", [
            "name" => $organization->name
        ]);

        return parent::getPage("HOME > WDEV", $content);
    }
}

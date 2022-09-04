<?php

use App\Controller\Pages;
use App\Http\Response;

$router->get("/", [
    function () {
        return new Response(200, Pages\Home::getHome());
    }
]);

$router->get("/sobre", [
    function () {
        return new Response(200, Pages\About::getAbout());
    }
]);

$router->get("/pagina/{idPage}/{action}", [
    function ($idPage, $action) {
        return new Response(200, "Página " . $idPage . " - " . $action);
    }
]);

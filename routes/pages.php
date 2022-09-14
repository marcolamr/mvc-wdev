<?php

use App\Controller\Pages;
use App\Http\Response;

$router->get("/", [
    "middlewares" => [
        "cache"
    ],
    function () {
        return new Response(200, Pages\Home::getHome());
    }
]);

$router->get("/sobre", [
    "middlewares" => [
        "cache"
    ],
    function () {
        return new Response(200, Pages\About::getAbout());
    }
]);

$router->get("/depoimentos", [
    "middlewares" => [
        "cache"
    ],
    function ($request) {
        return new Response(200, Pages\Testimony::getTestimonies($request));
    }
]);

$router->post("/depoimentos", [
    function ($request) {
        return new Response(200, Pages\Testimony::insertTestimony($request));
    }
]);

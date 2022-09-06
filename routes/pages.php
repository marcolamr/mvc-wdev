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

$router->get("/depoimentos", [
    function ($request) {
        return new Response(200, Pages\Testimony::getTestimonies($request));
    }
]);

$router->post("/depoimentos", [
    function ($request) {
        return new Response(200, Pages\Testimony::insertTestimony($request));
    }
]);

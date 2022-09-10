<?php

use App\Http\Response;
use App\Controller\Admin;

$router->get("/admin/testimonies", [
    "middlewares" => [
        "required-admin-login"
    ],
    function ($request) {
        return new Response(200, Admin\Testimony::getTestimonies($request));
    }
]);

$router->get("/admin/testimonies/new", [
    "middlewares" => [
        "required-admin-login"
    ],
    function ($request) {
        return new Response(200, Admin\Testimony::getNewTestimony($request));
    }
]);

$router->post("/admin/testimonies/new", [
    "middlewares" => [
        "required-admin-login"
    ],
    function ($request) {
        return new Response(200, Admin\Testimony::setNewTestimony($request));
    }
]);

$router->get("/admin/testimonies/{id}/edit", [
    "middlewares" => [
        "required-admin-login"
    ],
    function ($request, $id) {
        return new Response(200, Admin\Testimony::getEditTestimony($request, $id));
    }
]);

$router->post("/admin/testimonies/{id}/edit", [
    "middlewares" => [
        "required-admin-login"
    ],
    function ($request, $id) {
        return new Response(200, Admin\Testimony::setEditTestimony($request, $id));
    }
]);

$router->get("/admin/testimonies/{id}/delete", [
    "middlewares" => [
        "required-admin-login"
    ],
    function ($request, $id) {
        return new Response(200, Admin\Testimony::getDeleteTestimony($request, $id));
    }
]);

$router->post("/admin/testimonies/{id}/delete", [
    "middlewares" => [
        "required-admin-login"
    ],
    function ($request, $id) {
        return new Response(200, Admin\Testimony::setDeleteTestimony($request, $id));
    }
]);

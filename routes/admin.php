<?php

use App\Controller\Admin;
use App\Http\Response;

$router->get("/admin", [
    "middlewares" => [
        "required-admin-login"
    ],
    function () {
        return new Response(200, "Admin =)");
    }
]);

$router->get("/admin/login", [
    "middlewares" => [
        "required-admin-logout"
    ],
    function ($request) {
        return new Response(200, Admin\Login::getLogin($request));
    }
]);

$router->post("/admin/login", [
    "middlewares" => [
        "required-admin-logout"
    ],
    function ($request) {
        return new Response(200, Admin\Login::setLogin($request));
    }
]);

$router->get("/admin/logout", [
    "middlewares" => [
        "required-admin-login"
    ],
    function ($request) {
        return new Response(200, Admin\Login::setLogout($request));
    }
]);

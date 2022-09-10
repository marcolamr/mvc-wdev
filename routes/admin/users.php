<?php

use App\Http\Response;
use App\Controller\Admin;

$router->get("/admin/users", [
    "middlewares" => [
        "required-admin-login"
    ],
    function ($request) {
        return new Response(200, Admin\User::getUsers($request));
    }
]);

$router->get("/admin/users/new", [
    "middlewares" => [
        "required-admin-login"
    ],
    function ($request) {
        return new Response(200, Admin\User::getNewUser($request));
    }
]);

$router->post("/admin/users/new", [
    "middlewares" => [
        "required-admin-login"
    ],
    function ($request) {
        return new Response(200, Admin\User::setNewUser($request));
    }
]);

$router->get("/admin/users/{id}/edit", [
    "middlewares" => [
        "required-admin-login"
    ],
    function ($request, $id) {
        return new Response(200, Admin\User::getEditUser($request, $id));
    }
]);

$router->post("/admin/users/{id}/edit", [
    "middlewares" => [
        "required-admin-login"
    ],
    function ($request, $id) {
        return new Response(200, Admin\User::setEditUser($request, $id));
    }
]);

$router->get("/admin/users/{id}/delete", [
    "middlewares" => [
        "required-admin-login"
    ],
    function ($request, $id) {
        return new Response(200, Admin\User::getDeleteUser($request, $id));
    }
]);

$router->post("/admin/users/{id}/delete", [
    "middlewares" => [
        "required-admin-login"
    ],
    function ($request, $id) {
        return new Response(200, Admin\User::setDeleteUser($request, $id));
    }
]);

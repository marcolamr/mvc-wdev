<?php

require __DIR__ . "/../vendor/autoload.php";

use App\Http\Middleware\Queue as MiddlewareQueue;
use App\Utils\View;
use WilliamCosta\DatabaseManager\Database;
use WilliamCosta\DotEnv\Environment;

Environment::load(__DIR__ . "/../");

Database::config(
    getenv("DB_HOST"),
    getenv("DB_NAME"),
    getenv("DB_USER"),
    getenv("DB_PASS"),
    getenv("DB_PORT")
);

define("URL", getenv("URL"));

View::init([
    "URL" => URL
]);

MiddlewareQueue::setMap([
    "maintenance" => \App\Http\Middleware\Maintenance::class
]);

MiddlewareQueue::setDefault([
    "maintenance"
]);

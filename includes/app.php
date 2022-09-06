<?php

require __DIR__ . "/../vendor/autoload.php";

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

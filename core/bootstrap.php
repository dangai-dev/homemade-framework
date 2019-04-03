<?php

use Core\Router as Router;

$routes = require_once __DIR__ . "/../app/Routes/routes.php";
$router = new Router($routes);

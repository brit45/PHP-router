<?php

use Root22\Router\Controllers\HomeController;
use Root22\Router\Middlewares\Admin;
use Root22\Router\Middlewares\Guest;
use Root22\Router\Router;


$router = new Router;

$router->GET('/', [HomeController::class, 'anyView'])->middleware(Admin::class);

$router->GET('/a-propos', [HomeController::class, 'show'], 'home.about');
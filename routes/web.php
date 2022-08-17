<?php

use App\Controllers\HashesController;
use App\Controllers\HomeController;
use Equit\Contracts\Router;
use Equit\WebApplication;

/**
 * @var WebApplication $app
 * @var Router $router
 */

$router->registerGet("/", [HomeController::class, "showHomePage"]);

$router->registerGet("/hashes/random/{algorithm}", [HashesController::class, "showRandomHash"]);

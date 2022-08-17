<?php

use App\Controllers\HashesController;
use Equit\Contracts\Router;
use Equit\WebApplication;

/**
 * @var WebApplication $app
 * @var Router $router
 */

$router->registerGet("/api/hashes/{algorithm}/random", [HashesController::class, "sendRandomHash"]);
$router->registerPost("/api/hashes/{algorithm}/hash", [HashesController::class, "hashContent"]);

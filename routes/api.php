<?php

use App\Controllers\CodecsController;
use App\Controllers\HashesController;
use Bead\Contracts\Router;
use Bead\WebApplication;

/**
 * @var WebApplication $app
 * @var Router $router
 */

$router->registerGet("/api/hashes/{algorithm}/random", [HashesController::class, "sendRandomHash"]);
$router->registerPost("/api/hashes/{algorithm}/hash", [HashesController::class, "hashContent"]);
$router->registerPost("/api/hashes/{algorithm}/file/hash", [HashesController::class, "hashFile"]);

$router->registerPost("/api/codecs/{algorithm}/encode", [CodecsController::class, "encode"]);
$router->registerPost("/api/codecs/{algorithm}/decode", [CodecsController::class, "decode"]);

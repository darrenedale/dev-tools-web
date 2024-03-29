<?php

use App\Controllers\CodecsController;
use App\Controllers\HashesController;
use App\Controllers\HomeController;
use App\Controllers\RegexController;
use App\Controllers\UuidController;
use Bead\Contracts\Router;
use Bead\View;
use Bead\Core\WebApplication;

/**
 * @var WebApplication $app
 * @var Router $router
 */

$router->registerGet("/", [HomeController::class, "showHomePage"]);

$router->registerGet("/hashes/random/{algorithm}", [HashesController::class, "showRandomHash"]);
$router->registerGet("/hashes/{algorithm}", [HashesController::class, "showContentHash"]);
$router->registerGet("/hashes/{algorithm}/file", [HashesController::class, "showFileHash"]);

$router->registerGet("/uuid/random", [UuidController::class, "showRandomUuid"]);

$router->registerGet("/regex/tester", [RegexController::class, "showRegexTester"]);

$router->registerGet("/codec/{algorithm}", [CodecsController::class, "showCodec"]);
$router->registerGet("/decoder/{algorithm}", [CodecsController::class, "showDecoder"]);
$router->registerPost("/decoder/{algorithm}", [CodecsController::class, "decodeContent"]);
$router->registerPost("/decoder/{algorithm}/file", [CodecsController::class, "decodeFile"]);
$router->registerGet("/encoder/{algorithm}", [CodecsController::class, "showEncoder"]);
$router->registerPost("/encoder/{algorithm}", [CodecsController::class, "encodeFile"]);

$router->registerGet("/timestamp/convert", fn() => new View("timestamp", ["timestamp" => time(),]));

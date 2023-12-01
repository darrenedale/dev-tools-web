<?php

declare(strict_types=1);

use Bead\Core\Binders\Crypter;
use Bead\Core\Binders\Database;
use Bead\Core\Binders\Translator;
use Bead\Core\Binders\Logger;

return [
    // Whether to enable debug mode.
    //
    // If debug mode is enabled, any debugging messages will be appended to the log file. If not, debug messages will be
    // discarded.
    //
    // It is recommended you set this to false for production servers and true for development servers.
    "debugmode" => true,

    // The timezone for the app
    "timezone" => "europe/london",

    // The application's display title
    "title" => "Developer tools website",

    // where views for HTTP error response content are located
    "http.error.view.path" => "errors",

    "base_url" => getenv("DEV_TOOLS_URL") ?: ((($_SERVER["SERVER_NAME"] ?? "localhost") . (!in_array(($_SERVER["SERVER_PORT"] ?? 443), [80, 443,]) ? $_SERVER["SERVER_PORT"] : ""))),

    "binders" => [
        Logger::class,
        Translator::class,
        Crypter::class,
    ],
];

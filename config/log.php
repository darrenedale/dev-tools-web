<?php

declare(strict_types=1);

use Bead\Logging\FileLogger;

return [
    "driver" => "file",
    "file" => [
        "path" => "data/logs/dev-tools.log",
        "flags" => FileLogger::FlagAppend
    ],
];

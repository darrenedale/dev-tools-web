<?php

declare(strict_types=1);

use Bead\Logging\FileLogger;

return [
    "driver" => "file",
    "file" => [
        "path" => "data/logs/dead-drop.log",
        "flags" => FileLogger::FlagAppend
    ],
];

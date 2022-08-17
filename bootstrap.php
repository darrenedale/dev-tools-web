<?php

// bootstrapping that is common to both the web app and console commands

// register an autoloader for the app's classes
spl_autoload_register(function(string $className): void {
    if (!str_starts_with($className, "App\\")) {
        return;
    }

    $path = __DIR__ . "/app/" . str_replace("\\", "/", substr($className, 4)) . ".php";
    $file = new SplFileInfo($path);

    if (!$file->isFile() || !$file->isReadable()) {
        throw new LogicException("Class file for {$className} does not exist or can't be read.");
    }

    require_once($path);
});

// include the composer autoloader
require_once(__DIR__ . "/vendor/autoload.php");

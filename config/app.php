<?php

return [
    // The location of the app's log file.
    //
    // All error and warning messages will be appended to this file.
    // If debug mode is enabled, any debugging messages will also be written to this file.
    "logfile" => "logs/app.log",

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
];

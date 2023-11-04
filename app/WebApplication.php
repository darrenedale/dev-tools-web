<?php

namespace App;

use Bead\Contracts\Logger;
use Bead\Logging\FileLogger;
use Bead\WebApplication as BaseApplication;
use Exception;

/**
 * The app's singleton WebApplication class.
 */
class WebApplication extends BaseApplication
{
    /**
     * The constructor initialises the app's database connection, if configured.
     * @throws Exception If an Application instance has already been created.
     */
    public function __construct()
    {
        parent::__construct(__DIR__ . "/..");

        $this->setErrorHandler(new ErrorHandler());
    }
}
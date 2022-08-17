<?php

use App\WebApplication;

require_once(__DIR__ . "/../bootstrap.php");

(new WebApplication())->exec();

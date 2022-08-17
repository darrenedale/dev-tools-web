<?php

namespace App\Controllers;

use Equit\Contracts\Response;
use Equit\View;

class HomeController
{
    public function showHomePage(): Response
    {
        return new View("home");
    }
}

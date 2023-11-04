<?php

namespace App\Controllers;

use Bead\Contracts\Response;
use Bead\View;

class HomeController
{
    public function showHomePage(): Response
    {
        return new View("home");
    }
}

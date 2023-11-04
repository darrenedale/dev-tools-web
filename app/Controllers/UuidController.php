<?php

namespace App\Controllers;

use Bead\Contracts\Response;
use Bead\Request;
use Bead\View;

class UuidController
{
    public function showRandomUuid(Request $request): Response
    {
        return new View("random-uuid");
    }
}

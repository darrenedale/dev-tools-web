<?php

namespace App\Controllers;

use Equit\Contracts\Response;
use Equit\Request;
use Equit\View;

class UuidController
{
    public function showRandomUuid(Request $request): Response
    {
        return new View("random-uuid");
    }
}

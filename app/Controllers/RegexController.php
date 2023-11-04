<?php

namespace App\Controllers;

use Bead\Contracts\Response;
use Bead\Request;
use Bead\View;

class RegexController
{
    public function showRegexTester(Request $request): Response
    {
        return new View("regex-tester");
    }
}

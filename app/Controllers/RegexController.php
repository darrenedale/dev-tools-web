<?php

namespace App\Controllers;

use Equit\Contracts\Response;
use Equit\Request;
use Equit\View;

class RegexController
{
    public function showRegexTester(Request $request): Response
    {
        return new View("regex-tester");
    }
}

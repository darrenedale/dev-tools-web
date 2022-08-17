<?php

namespace App\Controllers;

use App\Responses\ApiResponse;
use Equit\Contracts\Response;
use Equit\Request;
use Equit\View;

class HashesController
{
    public function showRandomHash(Request $request, string $algorithm): Response
    {
        $hash = static::randomHash($algorithm);
        return new View("random-hash", compact("algorithm", "hash"));
    }

    public function sendRandomHash(Request $request, string $algorithm): Response
    {
        return (new ApiResponse(static::randomHash($algorithm)));
    }

    private static function randomHash(string $algorithm): string
    {
        return hash($algorithm, (string) (microtime(true) + mt_rand()));
    }
}

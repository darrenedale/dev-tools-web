<?php

namespace App\Controllers;

use App\Responses\ApiResponse;
use Equit\Contracts\Response;
use Equit\Request;
use Equit\Validation\Validator;
use Equit\View;

class HashesController
{
    public function showRandomHash(Request $request, string $algorithm): Response
    {
        $hash = static::randomHash($algorithm);
        return new View("random-hash", compact("algorithm", "hash"));
    }

    public function showContentHash(Request $request, string $algorithm): Response
    {
        return new View("content-hash", compact("algorithm"));
    }

    public function sendRandomHash(Request $request, string $algorithm): Response
    {
        return (new ApiResponse(static::randomHash($algorithm)));
    }

    private static function randomHash(string $algorithm): string
    {
        return hash($algorithm, (string) (microtime(true) + mt_rand()));
    }

    public function hashContent(Request $request, string $algorithm): Response
    {
        $validator = new Validator(
            $request->onlyPostData(["content"]),
            [
                "content" => ["string", "filled",],
            ]
        );

        if (!$validator->passes()) {
            return new ApiResponse(null, ApiResponse::CodeError, "No content provided to hash.");
        }

        extract($validator->validated());
        return (new ApiResponse(hash($algorithm, $content)));
    }
}

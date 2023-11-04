<?php

namespace App\Controllers;

use App\Responses\ApiResponse;
use Bead\Contracts\Response;
use Bead\Request;
use Bead\Validation\Validator;
use Bead\View;

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

    public function showFileHash(Request $request, string $algorithm): Response
    {
        return new View("file-hash", compact("algorithm"));
    }

    public function sendRandomHash(Request $request, string $algorithm): ApiResponse
    {
        return new ApiResponse(static::randomHash($algorithm));
    }

    private static function randomHash(string $algorithm): string
    {
        return hash($algorithm, (string) (microtime(true) + mt_rand()));
    }

    public function hashContent(Request $request, string $algorithm): ApiResponse
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

    public function hashFile(Request $request, string $algorithm): ApiResponse
    {
        $file = $request->uploadedFile("file");

        if (!isset($file)) {
            return ApiResponse::error("The file to hash was not provided.");
        }

        return (new ApiResponse(hash_file($algorithm, $file->tempFile())));
    }
}

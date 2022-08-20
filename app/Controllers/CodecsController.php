<?php

namespace App\Controllers;

use App\Responses\ApiResponse;
use App\Utilities\Base32;
use App\Utilities\Base64;
use App\Utilities\BinaryTextCodec;
use App\Utilities\Hexit;
use App\WebApplication;
use Equit\Contracts\Response;
use Equit\Request;
use Equit\Responses\DownloadResponse;
use Equit\Validation\Validator;
use Equit\View;
use InvalidArgumentException;

class CodecsController
{
    public function showCodec(Request $request, string $algorithm): Response
    {
        return new View("codec", compact("algorithm"));
    }

    public function showDecoder(Request $request, string $algorithm): Response
    {
        return new View("decoder", compact("algorithm"));
    }

    public function showEncoder(Request $request, string $algorithm): Response
    {
        return new View("encoder", compact("algorithm"));
    }

    public function decode(Request $request, string $algorithm): Response
    {
        $validator = new Validator(
            $request->onlyPostData(["encoded"]),
            [
                "encoded" => ["string", "filled",],
            ]
        );

        if (!$validator->passes()) {
            return ApiResponse::error("Nothing to decode.");
        }

        /** @var string $encoded */
        extract($validator->validated());

        try {
            $codec = self::codecForAlgorithm($algorithm);
            $codec->setEncoded($encoded);
            return (new ApiResponse(Hexit::encode($codec->raw())));
        } catch (InvalidArgumentException $err) {
            return ApiResponse::error($err->getMessage());
        }
    }

    public function encodeFile(Request $request, string $algorithm): Response
    {
        $file = $request->uploadedFile("file");

        if (!isset($file)) {
            WebApplication::instance()->storeTransientSessionData("messages", "errors", ["No file to encode."]);
            return new View("encoder", compact("algorithm"));
        }

        try {
            $codec = self::codecForAlgorithm($algorithm);
            $codec->setRaw($file->data());
            return (new DownloadResponse($codec->encoded()))->named(($file->name() ?? "uploaded-file") . ".{$algorithm}");
        } catch (InvalidArgumentException $err) {
            WebApplication::instance()->storeTransientSessionData("messages", "errors", [$err->getMessage()]);
            return new View("encoder", compact("algorithm"));
        }
    }

    public function decodeFile(Request $request, string $algorithm): Response
    {
        $validator = new Validator(
            $request->onlyPostData(["content"]),
            [
                "content" => ["string", "filled",],
            ]
        );

        if (!$validator->passes()) {
            return new View("decoder", compact("algorithm"));
        }

        /** @var string $encoded */
        extract($validator->validated());

        try {
            $codec = self::codecForAlgorithm($algorithm);
            $codec->setEncoded($content);
            return (new DownloadResponse($codec->raw()))->named("{$algorithm}-decoded-file");
        } catch (InvalidArgumentException $err) {
            WebApplication::instance()->storeTransientSessionData("messages", "errors", [$err->getMessage()]);
            return new View("decoder", compact("algorithm"));
        }
    }

    public function encode(Request $request, string $algorithm): ApiResponse
    {
        $validator = new Validator(
            $request->onlyPostData(["raw"]),
            [
                "raw" => ["string", "filled",],
            ]
        );

        if (!$validator->passes()) {
            return ApiResponse::error("Nothing to encode.");
        }

        /** @var string $raw */
        extract($validator->validated());

        try {
            $codec = self::codecForAlgorithm($algorithm);
            $codec->setRaw($raw);
            return (new ApiResponse($codec->encoded()));
        } catch (InvalidArgumentException $err) {
            return ApiResponse::error($err->getMessage());
        }
    }

    private static function codecForAlgorithm(string $algorithm): BinaryTextCodec
    {
        return match(strtolower($algorithm)) {
            "base32" => new Base32(),
            "base64" => new Base64(),
            default => throw new InvalidArgumentException("Unrecognised binary-to-text encoding algorithm '{$algorithm}'."),
        };
    }
}

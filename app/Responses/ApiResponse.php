<?php

declare(strict_types=1);

namespace App\Responses;

use Equit\Contracts\Response;
use Equit\Responses\DoesntHaveHeaders;
use Equit\Responses\NaivelySendsContent;

/**
 * Encapsulation of an API response.
 *
 * Responses are always JSON. They are an object with two properties:
 * - result
 * - payload
 *
 * The result is an object with two properties:
 * - code [int] (0 for success, non-0 for failure)
 * - message [string] An informative message that can be shown to the user.
 *
 * The payload is whatever the API call requires. If no payload is required, it will be `null`. THE `payload` property
 * IS ALWAYS PRESENT, EVEN IF THE RESPONSE DOES NOT NEED ONE.
 *
 * Example minimal response:
 *
 * ```json
 * {
 *     "result": {
 *         "code": 0,
 *         "message": "OK"
 *     },
 *     "payload": null
 * }
 */
class ApiResponse implements Response
{
    use DoesntHaveHeaders;
    use NaivelySendsContent;

    public const CodeOk = 0;
    public const CodeError = 1;
    public const CodeUserBase = 100;

    /** @var int API call result code. */
    private int $m_code;

    /** @var string API call result message. */
    private string $m_message;

    /** @var mixed|null API response payload. */
    private mixed $m_payload;

    public function __construct(mixed $payload = null, int $code = self::CodeOk, string $message = "OK")
    {
        $this->m_code = $code;
        $this->m_message = $message;
        $this->m_payload = $payload;
    }

    /**
     * @return int The HTTP status code.
     */
    public function statusCode(): int
    {
        return 200;
    }

    /**
     * @return string HTTP content type.
     */
    public function contentType(): string
    {
        return "application/json";
    }

    /**
     * Fluently set the API response payload.
     *
     * @param mixed $payload The payload.
     * @return $this The ApiResponse object for further method chaining.
     */
    public function withPayload(mixed $payload): self
    {
        $this->setPayload($payload);
        return $this;
    }

    /**
     * Set the API response payload.
     *
     * @param mixed $payload The payload.
     */
    public function setPayload(mixed $payload): void
    {
        $this->m_payload = $payload;
    }

    /**
     * Fetch the API response payload.
     *
     * @return mixed The payload.
     */
    public function payload(): mixed
    {
        return $this->m_payload;
    }

    /**
     * Fluently set the API response result code.
     * @param int $code The code.
     * @return $this The ApiResponse for further method chaining.
     */
    public function withCode(int $code): self
    {
        $this->setCode($code);
        return $this;
    }

    /**
     * Set the API response result code.
     * @param int $code The code.
     */
    public function setCode(int $code): void
    {
        $this->m_code = $code;
    }

    /**
     * Fetch the API response result code.
     * @return int The code.
     */
    public function code(): int
    {
        return $this->m_code;
    }

    /**
     * Fluently set the API response result message.
     * @param string $message The message.
     * @return $this The ApiResponse for further method chaining.
     */
    public function withMessage(string $message): self
    {
        $this->setMessage($message);
        return $this;
    }

    /**
     * Set the API response result message.
     * @param string $message The message.
     */
    public function setMessage(string $message): void
    {
        $this->m_message = $message;
    }

    /**
     * Fetch the API response result message.
     * @return string The message.
     */
    public function message(): string
    {
        return $this->m_message;
    }

    /**
     * Customisation point for the JSON representation of the payload.
     *
     * Reimplement this in subclasses if you need to customise how the payload is represented as JSON. The default
     * implementation just returns the payload unmodified, on the assumption that it is suitable for encoding with
     * `json_encode()`.
     *
     * @return mixed The JSON for the payload.
     */
    protected function payloadJson(): mixed
    {
        return $this->payload();
    }

    /**
     * Customisation point for the JSON representation of the result object.
     *
     * Reimplement this in subclasses if you need to customise how the result is represented as JSON. You shold only
     * need to use this if you want to extend the result object. It is very strongly recommended that you don't change
     * how the result object's code and message properties are represented..
     *
     * @return mixed The JSON for the result object.
     */
    protected function resultJson(): array
    {
        return [
            "code" => $this->code(),
            "message" => $this->code(),
        ];
    }

    /**
     * @inheritDoc
     */
    public function content(): string
    {
        return json_encode([
            "result" => $this->resultJson(),
            "payload" => $this->payloadJson(),
        ]);
    }
}

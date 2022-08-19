<?php

namespace App\Utilities;

use RuntimeException;

final class Base64 extends BinaryTextCodec
{
    /**
     * @invariant One or other of these may be null at any given time, but never both.
     * @var string|null The encoded data.
     */
    private ?string $m_encoded = null;
    private ?string $m_raw = "";

    public function __construct()
    {}

    public function raw(): string
    {
        if (!isset($this->m_raw)) {
            $this->m_raw = self::decodeBase64($this->m_encoded);
        }

        return $this->m_raw;
    }

    public function setRaw(string $raw): void
    {
        $this->m_raw = $raw;
        $this->m_encoded = null;
    }

    public function encoded(): string
    {
        if (!isset($this->m_encoded)) {
            $this->m_encoded = self::encodeBase64($this->m_raw);
        }

        return $this->m_encoded;
    }

    public function setEncoded(string $encoded): void
    {
        $this->m_encoded = $encoded;
        $this->m_raw = null;
    }

    private static function decodeBase64(string $encoded): string
    {
        $raw = base64_decode($encoded, true);

        if (false === $raw) {
            throw new RuntimeException("Invalid bas64 data.");
        }

        return $raw;
    }

    private static function encodeBase64(string $raw): string
    {
        return base64_encode($raw);
    }
}

<?php

namespace App\Utilities;

final class Base32 extends BinaryTextCodec
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
            $this->m_raw = self::decodeBase32($this->m_encoded);
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
            $this->m_encoded = self::encodeBase32($this->m_raw);
        }

        return $this->m_encoded;
    }

    public function setEncoded(string $encoded): void
    {
        $this->m_encoded = $encoded;
        $this->m_raw = null;
    }

    private static function decodeBase32(string $encoded): string
    {
        // TODO implementation
        return "";
    }

    private static function encodeBase32(string $raw): string
    {
        // TODO implementation
        return "";
    }
}

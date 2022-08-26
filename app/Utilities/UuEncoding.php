<?php

namespace App\Utilities;

use RuntimeException;

class UuEncoding extends BinaryTextCodec
{
    /**
     * @invariant One or other of these may be null at any given time, but never both.
     * @var string|null The encoded data.
     */
    private ?string $m_encoded = null;
    private ?string $m_raw = "";

    public function raw(): string
    {
        if (!isset($this->m_raw)) {
            $this->m_raw = self::uuDecoded($this->m_encoded);
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
            $this->m_encoded = self::uuEncoded($this->m_raw);
        }

        return $this->m_encoded;
    }

    public function setEncoded(string $encoded): void
    {
        $this->m_encoded = $encoded;
        $this->m_raw = null;
    }

    private static function uuDecoded(string $encoded): string
    {
        $raw = convert_uudecode($encoded);

        if (false === $raw) {
            throw new RuntimeException("Invalid UUEncoded data.");
        }

        return $raw;
    }

    private static function uuEncoded(string $raw): string
    {
        return convert_uuencode($raw);
    }
}
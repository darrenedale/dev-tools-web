<?php

namespace App\Utilities;

use RuntimeException;

final class Hexit extends BinaryTextCodec
{
    private ?string $m_encoded = null;
    private ?string $m_raw = "";

    public function raw(): string
    {
        if (!isset($this->m_raw)) {
            $this->m_raw = self::fromHexits($this->m_encoded);
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
            $this->m_encoded = self::fromHexits($this->m_raw);
        }

        return $this->m_encoded;
    }

    public function setEncoded(string $encoded): void
    {
        $this->m_encoded = $encoded;
        $this->m_raw = null;
    }


    private static function toHexits(string $raw): string
    {
        $encoded = "";

        for ($idx = 0; $idx < strlen($raw); ++$idx) {
            $encoded .= sprintf("%02x", ord($raw[$idx]));
        }

        return $encoded;
    }


    private static function fromHexits(string $encoded): string
    {
        $len = strlen($encoded);

        if (0 !== $len % 2 || $len !== strspn($encoded, "0123456789abcdefABCDEF")) {
            throw new RuntimeException("Invalid encoded Hexits data.");
        }

        $raw = "";

        for ($idx = 0; $idx < strlen($encoded); $idx += 2) {
            $raw .= chr(hexdec(substr($encoded, $idx, 2)));
        }

        return $raw;
    }
}

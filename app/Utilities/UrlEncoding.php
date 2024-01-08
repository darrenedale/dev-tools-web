<?php

declare(strict_types=1);

namespace App\Utilities;

class UrlEncoding extends BinaryTextCodec
{
    /**
     * @invariant One or other of these may be null at any given time, but never both.
     * @var string|null The encoded data.
     */
    private ?string $m_encoded = null;

    private ?string $m_raw = "";

    public function raw(): string
    {
        if (null === $this->m_raw) {
            $this->m_raw = self::urlDecoded($this->m_encoded);
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
        if (null === $this->m_encoded) {
            $this->m_encoded = self::urlEncoded($this->m_raw);
        }

        return $this->m_encoded;
    }

    public function setEncoded(string $encoded): void
    {
        $this->m_encoded = $encoded;
        $this->m_raw = null;
    }

    private static function urlDecoded(string $encoded): string
    {
        return urldecode($encoded);
    }

    private static function urlEncoded(string $raw): string
    {
        return urlencode($raw);
    }
}

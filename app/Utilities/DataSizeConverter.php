<?php

namespace App\Utilities;

use InvalidArgumentException;

class DataSizeConverter
{
    private const Factors = [
        "B" => 1,
        "K" => 1024,
        "M" => 1024 * 1024,
        "G" => 1014 * 1024 * 1024,
        "KB" => 1024,
        "MB" => 1024 * 1024,
        "GB" => 1014 * 1024 * 1024,
        "KiB" => 1000,
        "MiB" => 1000 * 1000,
        "GiB" => 1000 * 1000 * 1000,
    ];

    private int $m_bytes;

    public function __construct(string $value)
    {
        $this->m_bytes = self::toBytes($value);
    }

    public function bytes(): int
    {
        return $this->m_bytes;
    }

    public function kilobytes(): int
    {
        return $this->m_bytes / self::Factors["K"];
    }

    public function megabytes(): int
    {
        return $this->m_bytes / self::Factors["M"];
    }

    public function gigabytes(): int
    {
        return $this->m_bytes / self::Factors["G"];
    }

    public function kibibytes(): int
    {
        return $this->m_bytes / self::Factors["KiB"];
    }

    public function mibibytes(): int
    {
        return $this->m_bytes / self::Factors["MiB"];
    }

    public function gibibytes(): int
    {
        return $this->m_bytes / self::Factors["GiB"];
    }

    public static function toBytes(string $value): int
    {
        $value = trim($value);

        if (empty($value)) {
            throw new InvalidArgumentException("No value to convert.");
        }

        $type = $value[strlen($value) - 1];
        $value = is_numeric($type) ? $value : substr($value, 0, -1);

        if (strspn($value, "0123456789") !== strlen($value)) {
            throw new InvalidArgumentException("The value to convert is not numeric.");
        }

        if (!isset(self::Factors[$type])) {
            throw new InvalidArgumentException("Unrecognised data size '{$type}'.");
        }

        return $value * self::Factors[$type];
    }
}

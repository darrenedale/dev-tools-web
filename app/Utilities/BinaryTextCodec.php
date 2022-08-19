<?php

namespace App\Utilities;

abstract class BinaryTextCodec
{
    public abstract function raw(): string;
    public abstract function setRaw(string $raw): void;
    public abstract function encoded(): string;
    public abstract function setEncoded(string $encoded): void;

    /**
     * Polymorphic static convenience method to encode some data.
     *
     * The class on which this is invoked must have a default constructor.
     *
     * @param string $raw The data to encode.
     *
     * @return string The encoded data.
     */
    public static function encode(string $raw): string
    {
        $codec = new static();
        $codec->setRaw($raw);
        return $codec->encoded();
    }

    /**
     * Polymorphic static convenience method to decode some data.
     *
     * The class on which this is invoked must have a default constructor.
     *
     * @param string $encoded The encoded data do decode.
     *
     * @return string The raw data.
     */
    public static function decode(string $encoded): string
    {
        $codec = new static();
        $codec->setEncoded($encoded);
        return $codec->raw();
    }
}

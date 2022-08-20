<?php

namespace App\Utilities;

use InvalidArgumentException;

final class Base32 extends BinaryTextCodec
{
    /**
     * The base32 dictionary.
     */
    private const Dictionary = "ABCDEFGHIJKLMNOPQRSTUVWXYZ234567";

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
        $length = strlen($encoded);

        if (0 !== ($length % 8)) {
            throw new InvalidArgumentException($encoded, "Base32 data must be padded to a multiple of 8 bytes.");
        }

        // ensure any padding is a valid length
        $paddedLength = $length;

        while (0 < $length && $encoded[$length - 1] === "=") {
            --$length;
        }

        switch ($paddedLength - $length) {
            case 0:
            case 1:
            case 3:
            case 4:
            case 6:
                break;

            default:
                throw new InvalidArgumentException("Base32 data must be padded with either 0, 1, 3, 4 or 6 '=' characters.");
        }

        // ensure all non-padding characters are from the Base32 dictionary
        $validLength = strspn($encoded, self::Dictionary, 0, $length);

        if ($length !== $validLength) {
            throw new InvalidArgumentException("Invalid base32 character found at position {$validLength}.");
        }

        $this->m_encoded = $encoded;
        $this->m_raw = null;
    }

    private static function decodeBase32(string $encoded): string
    {
        $byteSequence = strtoupper($encoded);

        // tolerate badly terminated encoded strings by padding with = to appropriate len
        $len = strlen($byteSequence);

        if (0 === $len) {
            return "";
        }

        $raw = "";
        $remainder = $len % 8;

        if (0 < $remainder) {
            $byteSequence .= str_repeat("=", 8 - $remainder);
            $len += 8 - $remainder;
        }

        for ($i = 0; $i < $len; $i += 8) {
            $out = 0x00;

            for ($j = 0; $j < 8; ++$j) {
                if ("=" == $byteSequence[$i + $j]) {
                    break;
                }

                $pos = strpos(self::Dictionary, $byteSequence[$i + $j]);
                assert(false !== $pos, "Found invalid base32 character at position " . ($i + $j) . " - setEncoded() should ensure this can never happen.");
                $out <<= 5;
                $out |= ($pos & 0x1f);
            }

            /* in any chunk we must have processed either 2, 4, 5, 7 or 8 bytes */
            [$outByteCount, $out] = match ($j) {
                8 => [5, $out],
                7 => [4, $out << 5],
                5 => [3, $out << 15],
                4 => [2, $out << 20],
                2 => [1, $out << 30],
                // NOTE this should never happen
                default => assert(false, "Processed invalid chunk size - error in Base32 decoding algorithm implementation."),
            };

            $outBytes = chr(($out >> 32) & 0xff)
                . chr(($out >> 24) & 0xff)
                . chr(($out >> 16) & 0xff)
                . chr(($out >> 8) & 0xff)
                . chr($out & 0xff);
            $raw .= substr($outBytes, 0, $outByteCount);
        }

        return $raw;
    }

    private static function encodeBase32(string $raw): string
    {
        $len = strlen($raw);

        if (0 == $len) {
            return "";
        }

        $encoded = "";
        $paddedLen = (int) ceil($len / 5.0) * 5;

        if ($paddedLen !== $len) {
            // pad so that we've a multiple of 5 characters to encode
            $raw .= str_repeat("\0", $paddedLen - $len);
        }

        $pos = 0;

        while ($pos < $paddedLen) {
            // 5 chars of raw convert to 8 chars of base32. the 40 bits of the 5 chars are read in 5-bit chunks,
            // each of which is the index of a base32 character in Dictionary
            $bits = 0x00 | (ord($raw[$pos]) << 32)
                | (ord($raw[$pos + 1]) << 24)
                | (ord($raw[$pos + 2]) << 16)
                | (ord($raw[$pos + 3]) << 8)
                | (ord($raw[$pos + 4]));

            // the bit pattern contains the groups of 5 bits that form the dictionary lookup indices from left to
            // right:
            // bit                     :  39 ... 35 .... 30 .... 25 .... 20 .... 15 .... 10 .... 5 .... 0
            // encoded character offset:  |   0    |   1   |   2   |   3   |   4   |   5   |   6  |  7  |
            //
            // so the next encoded character is identified by bits 35-39, the one after that by bits 30-34 and
            // so on until the eighth encoded character, represented by bits 0-4.
            //
            // this means that we can't just use 0x1f for the mask to successively take the rightmost 5 bits
            // (shifting the bits 5 to the right as we go) and append the appropriate dictionary character to the
            // encoded data. this would result in the right characters but in reverse order. so we need to start
            // with the mask extracting the leftmost 5 bits for the first character and shift the mask in each
            // iteration to extract the next 5 bits, and we need to track how far to shift the extracted bits so
            // that they represent a valid Dictionary index. hence, $mask and $shift
            $shift = 35;
            $mask  = 0x1f << $shift;

            while (0 !== $mask) {
                $encoded .= self::Dictionary[($bits & $mask) >> $shift];
                $mask >>= 5;
                $shift -= 5;
            }

            $pos += 5;
        }

        // to keep things simple we've padded the data and therefore produced extraneous encoded data. this works
        // out how much to replace with '=' characters
        $encodedPadding = match ($paddedLen - $len) {
            0 => 0,
            1 => 1,
            2 => 3,
            3 => 4,
            4 => 6,
        };

        // undo the temporary padding of the raw data and pad the encoded data
        if (0 != $encodedPadding) {
            $encoded = substr($encoded, 0, -$encodedPadding) . str_repeat("=", $encodedPadding);
        }

        return $encoded;
    }
}

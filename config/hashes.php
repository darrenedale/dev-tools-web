<?php

return [
    // the available algorithms for the hashing utils - only the intersection of this and the algorithms supported by
    // PHP will actually be available
    "algorithms" => [
        "adler32", "crc32", "md4", "md5", "sha1", "sha224", "sha256", "sha384", "sha512",
        "sha3-224", "sha3-256", "sha3-384", "sha3-512",
    ],
];

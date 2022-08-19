<?php
/**
 * @var $app \App\WebApplication
 */
?>
<ul>
    <li>
        <a href="/hashes">Hashes</a>
        <ul>
            <?php foreach (array_intersect($app->config("hashes.algorithms"), hash_algos()) as $algorithm): ?>
            <li>
                <a href="/hashes/<?= rawurlencode($algorithm) ?>"><?= html($algorithm) ?></a>
                <ul>
                    <li>
                        <a href="/hashes/<?= rawurlencode($algorithm) ?>">Calculator</a>
                    </li>
                    <li>
                        <a href="/hashes/random/<?= rawurlencode($algorithm) ?>">Random generator</a>
                    </li>
                </ul>
            </li>
            <?php endforeach; ?>
        </ul>
    </li>
    <li>
        <a href="/regex/tester">Regex</a>
        <ul>
            <li>
                <a href="/regex/tester">Regex Tester</a>
            </li>
        </ul>
    </li>
    <li>
        <a href="/codec/base64">Codecs</a>
        <ul>
            <li>
                <a href="/codec/base64">Base64</a>
                <ul>
                    <li>
                        <a href="/codec/base64">Encoder/Decoder</a>
                    </li>
                    <li>
                        <a href="/decoder/base64">Decode file</a>
                    </li>
                </ul>
            </li>
            <li>
                <a href="/codec/base32">Base32</a>
                <ul>
                    <li>
                        <a href="/codec/base32">Encoder/Decoder</a>
                    </li>
                    <li>
                        <a href="/decoder/base32">Decode file</a>
                    </li>
                </ul>
            </li>
        </ul>
    </li>
</ul>

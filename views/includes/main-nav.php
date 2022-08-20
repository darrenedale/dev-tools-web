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
            <?php foreach(["base64", "base32",] as $algorithm): ?>
            <li>
                <a href="/codec/<?= $algorithm ?>"><?= ucfirst($algorithm) ?></a>
                <ul>
                    <li>
                        <a href="/codec/<?= $algorithm ?>">Live Encoder/Decoder</a>
                    </li>
                    <li>
                        <a href="/decoder/<?= $algorithm ?>">Decode file</a>
                    </li>
                    <li>
                        <a href="/encoder/<?= $algorithm ?>">Encode file</a>
                    </li>
                </ul>
            </li>
            <?php endforeach; ?>
        </ul>
    </li>
    <li>
        <a href="/timestamp/convert">Timestamps</a>
        <ul>
            <li>
                <a href="/timestamp/convert">Convert</a>
            </li>
        </ul>
    </li>
</ul>

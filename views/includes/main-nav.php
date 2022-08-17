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
</ul>

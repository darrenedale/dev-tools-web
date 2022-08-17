<ul>
    <li>
        <a href="/hashes">Hashes</a>
        <ul>
            <?php foreach (hash_algos() as $algorithm): ?>
            <li>
                <a href="/hashes/random/<?= rawurlencode($algorithm) ?>">Random <?= html($algorithm) ?></a>
            </li>
            <?php endforeach; ?>
        </ul>
    </li>
</ul>

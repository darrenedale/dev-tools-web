<?php

/**
 * @var $app WebApplication The WebApplication instance.
 * @var $algorithm string The codec algorithm to use.
 * @var $encoded string|null The encoded data to display.
 * @var $raw string|null The raw data to display.
 */

use Bead\View;
use Bead\Core\WebApplication;

View::layout("layouts.main");
?>

<?php View::push("scripts"); ?>
<script type="module" src="/scripts/Codec.js"></script>
<?php View::endPush(); ?>

<?php View::section("head-title"); ?>
Encode/decode <?= html($algorithm) ?>
<?php
View::endSection();
View::section("page-title");
?>
Encode/decode <?= html(mb_strtoupper($algorithm)) ?>
<?php View::endSection(); ?>

<?php View::section("content"); ?>
<div class="codec-container" data-algorithm="<?= html($algorithm) ?>" data-csrf="<?= html($app->csrf()) ?>">
    <div class="codec-content-container codec-raw-container">
        <textarea class="codec-raw" title="The content to encode." placeholder="Enter some content to encode..."><?= html($raw ?? "") ?></textarea>
        <span>
            <button class="codec-copy codec-copy-raw-binary" title="Copy the raw content to the clipboard as binary data."><span class="fa-regular fa-copy"></span></button>
            <button class="codec-copy codec-copy-raw-hexits" title="Copy the raw content to the clipboard as hexits."><span class="fa-regular fa-copy"></span></button>
        </span>
    </div>
    <div class="codec-content-container codec-encoded-container">
        <textarea class="codec-encoded" title="The <?= html($algorithm) ?> to decode." placeholder="Enter some content to decode..."><?= html($encoded ?? "") ?></textarea>
        <button class="codec-copy codec-copy-encoded" title="Copy the encoded content to the clipboard."><span class="fa-regular fa-copy"></span></button>
    </div>
</div>
<?php View::endSection(); ?>

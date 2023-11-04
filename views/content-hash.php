<?php

/**
 * @var $app WebApplication The WebApplication instance.
 * @var $algorithm string The hashing algorithm to display.
 * @var $hash string|null The has to display.
 */

use Bead\View;
use Bead\WebApplication;

View::layout("layouts.main");
?>

<?php View::push("scripts"); ?>
<script type="module" src="/scripts/ContentHash.js"></script>
<?php View::endPush(); ?>

<?php View::section("head-title"); ?>
Calculate <?= html($algorithm) ?>
<?php
View::endSection();
View::section("page-title");
?>
Calculate <?= html(mb_strtoupper($algorithm)) ?>
<?php View::endSection(); ?>

<?php View::section("content"); ?>
<div class="hash-container content-hash" data-algorithm="<?= html($algorithm) ?>" data-csrf="<?= html($app->csrf()) ?>">
    <textarea class="hash-source" title="The content to hash." placeholder="Enter some content to hash..."><?= html($content ?? "") ?></textarea>
    <div class="hash-display-container">
        <div class="hash-display"><?= html($hash ?? "<no hash>") ?></div>
        <span>
            <input id="content-hash-<?= html($algorithm) ?>-upper" class="hash-upper" type="checkbox" />
            <label for="content-hash-<?= html($algorithm) ?>-upper">Upper case</label>
        </span>
        <button class="hash-copy" title="Copy this <?= html($algorithm) ?> hash to clipboard."><span class="fa-regular fa-copy"></span></button>
    </div>
</div>
<?php View::endSection(); ?>

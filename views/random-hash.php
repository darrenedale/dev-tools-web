<?php

/**
 * @var $app WebApplication The WebApplication instance.
 * @var $algorithm string The hashing algorithm to display.
 * @var $hash string|null The has to display.
 */

use Bead\View;
use Bead\Core\WebApplication;

View::layout("layouts.main");
?>

<?php View::push("scripts"); ?>
<script type="module" src="/scripts/RandomHash.js"></script>
<?php View::endPush(); ?>

<?php View::section("head-title"); ?>
Random <?= html($algorithm) ?>
<?php
View::endSection();
View::section("page-title");
?>
Random <?= html(mb_strtoupper($algorithm)) ?>
<?php View::endSection(); ?>

<?php View::section("content"); ?>
<div class="hash-container random-hash" data-algorithm="<?= html($algorithm) ?>">
    <div class="hash-display-container">
        <div class="hash-display"><?= html($hash ?? "") ?></div>
        <span>
            <input id="random-hash-<?= html($algorithm) ?>-upper" class="hash-upper" type="checkbox" />
            <label for="random-hash-<?= html($algorithm) ?>-upper">Upper case</label>
        </span>
        <button class="hash-copy" title="Copy this <?= html($algorithm) ?> hash to clipboard."><span class="fa-regular fa-copy"></span></button>
        <button class="hash-refresh" title="Generate a new random <?= html($algorithm) ?> hash."><span class="fa-solid fa-repeat"></span></button>
    </div>
</div>
<?php View::endSection(); ?>

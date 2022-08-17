<?php

/**
 * @var $app WebApplication The WebApplication instance.
 * @var $algorithm string The hashing algorithm to display.
 * @var $hash string|null The has to display.
 */

use Equit\View;
use Equit\WebApplication;

View::layout("layouts.main");
?>

<?php View::push("scripts"); ?>
<script type="module" src="/scripts/RandomHash.js"></script>
<?php View::endPush(); ?>

<?php View::push("styles"); ?>
<link rel="stylesheet" href="/styles/random-hash.css" />
<?php View::endPush(); ?>

<?php View::section("head-title"); ?>
Random MD5
<?php
View::endSection();
View::section("page-title");
?>
Random <?= html(mb_strtoupper($algorithm)) ?>
<?php View::endSection(); ?>

<?php View::section("content"); ?>
<div class="random-hash" data-algorithm="<?= html($algorithm) ?>">
    <div class="random-hash-display"><?= html($hash ?? "") ?></div>
    <input id="random-hash-<?= html($algorithm) ?>" class="random-hash-upper" type="checkbox" /><label for="random-hash-<?= html($algorithm) ?>">Upper case</label>
    <button class="random-hash-copy" title="Copy this <?= html($algorithm) ?> hash to clipboard."><span class="fa-regular fa-copy"></span></button>
    <button class="random-hash-refresh" title="Generate a new random <?= html($algorithm) ?> hash."><span class="fa-solid fa-repeat"></span></button>
</div>
<?php View::endSection(); ?>

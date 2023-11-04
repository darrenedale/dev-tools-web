<?php

/**
 * @var $app WebApplication The WebApplication instance.
 * @var $algorithm string The uuiding algorithm to display.
 * @var $uuid string|null The has to display.
 */

use Bead\View;
use Bead\WebApplication;

View::layout("layouts.main");
?>

<?php View::push("scripts"); ?>
<script type="module" src="/scripts/RandomUuid.js"></script>
<?php View::endPush(); ?>

<?php View::section("head-title"); ?>
Random UUID
<?php
View::endSection();
View::section("page-title");
?>
Random UUID
<?php View::endSection(); ?>

<?php View::section("content"); ?>
<div class="uuid-container random-uuid">
    <div class="uuid-display-container">
        <div class="uuid-display"><?= html($uuid ?? "") ?></div>
        <span>
            <input id="random-uuid-upper" class="uuid-upper" type="checkbox" />
            <label for="random-uuid-upper">Upper case</label>
        </span>
        <button class="uuid-copy" title="Copy this UUID to clipboard."><span class="fa-regular fa-copy"></span></button>
        <button class="uuid-refresh" title="Generate a new random UUID."><span class="fa-solid fa-repeat"></span></button>
    </div>
</div>
<?php View::endSection(); ?>

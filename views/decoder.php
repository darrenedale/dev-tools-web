<?php

/**
 * @var $app WebApplication The WebApplication instance.
 * @var $algorithm string The codec algorithm to use.
 * @var $encoded string|null The encoded data to display.
 * @var $raw string|null The raw data to display.
 */

use Equit\View;
use Equit\WebApplication;

View::layout("layouts.main");
?>

<?php View::push("scripts"); ?>
<script type="module" src="/scripts/Decoder.js"></script>
<?php View::endPush(); ?>

<?php View::section("head-title"); ?>
Decode <?= html(mb_convert_case($algorithm, MB_CASE_TITLE)) ?> file
<?php
View::endSection();
View::section("page-title");
?>
Decode <?= html(mb_convert_case($algorithm, MB_CASE_TITLE)) ?> file
<?php View::endSection(); ?>

<?php View::section("content"); ?>
<form action="/decoder/<?= html($algorithm) ?>/" method="POST">
    <div class="decoder-container" data-algorithm="<?= html($algorithm) ?>" data-csrf="<?= html($app->csrf()) ?>">
        <?php View::csrf() ?>
        <textarea name="content" class="decoder-content" title="The content to decode." placeholder="Enter some <?= html($algorithm) ?> content to decode..."><?= html($raw ?? "") ?></textarea>
        <span>
            <button type="submit" class="decoder-decode" title="Decode the content."><span class="fa-solid fa-download"></span>&nbsp;Decode</button>
            <button type="button" class="decoder-clear" title="Clear the content."><span class="fa-solid fa-delete-left"></span>&nbsp;Clear</button>
        </span>
    </div>
</form>
<?php View::endSection(); ?>

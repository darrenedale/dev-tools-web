<?php

/**
 * @var $app WebApplication The WebApplication instance.
 * @var $algorithm string The codec algorithm to use.
 * @var $encoded string|null The encoded data to display.
 * @var $raw string|null The raw data to display.
 */

use Bead\View;
use Bead\WebApplication;

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
<form action="/decoder/<?= html($algorithm) ?>/" method="POST" enctype="multipart/form-data">
    <div class="decoder-container" data-algorithm="<?= html($algorithm) ?>">
        <?php View::csrf() ?>
        <textarea name="content" class="decoder-content" title="The content to decode." placeholder="Enter some <?= html($algorithm) ?> content to decode..."><?= html($raw ?? "") ?></textarea>
        <input type="file" name="file" class="decoder-file" title="The file to decode." />
        <span>
            <button type="submit" class="decoder-decode" title="Decode the content."><span class="fa-solid fa-download"></span>&nbsp;Decode</button>
            <button type="button" class="decoder-clear" title="Clear the content."><span class="fa-solid fa-delete-left"></span>&nbsp;Clear</button>
        </span>
    </div>
</form>
<?php View::endSection(); ?>

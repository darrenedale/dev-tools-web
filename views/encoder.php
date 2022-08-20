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
<script type="module" src="/scripts/Encoder.js"></script>
<?php View::endPush(); ?>

<?php View::section("head-title"); ?>
Encode file using <?= html($algorithm) ?>
<?php
View::endSection();
View::section("page-title");
?>
Encode file using <?= html(mb_strtoupper($algorithm)) ?>
<?php View::endSection(); ?>

<?php View::section("content"); ?>
<form action="/encoder/<?= html($algorithm) ?>/" method="POST" enctype="multipart/form-data">
    <div class="encoder-container" data-algorithm="<?= html($algorithm) ?>" data-csrf="<?= html($app->csrf()) ?>">
        <?php View::csrf() ?>
        <input type="file" name="file" class="encoder-content" title="Choose a file to encode.">
        <button type="submit" class="encoder-encode" title="Encode the file."><span class="fa-solid fa-download"></span>&nbsp;Encode</button>
    </div>
</form>
<?php View::endSection(); ?>

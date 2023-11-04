<?php

/**
 * @var $app WebApplication The WebApplication instance.
 * @var $algorithm string The hashing algorithm to display.
 * @var $hash string|null The has to display.
 */

use App\Utilities\DataSizeConverter;
use Bead\View;
use Bead\WebApplication;

View::layout("layouts.main");
?>

<?php View::push("scripts"); ?>
<script type="module" src="/scripts/FileHash.js"></script>
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
<p>
    To hash a file it needs to be uploaded to our server. The uploaded file is not stored, it is discarded as soon as
    the hash has been calculated.
</p>

<?php if (0 !== (int) ini_get("upload_max_filesize")): ?>
<p>There is a <?= (new DataSizeConverter(ini_get("upload_max_filesize")))->megabytes() ?>Mb upload limit.</p>
<?php endif; ?>

<div class="hash-container file-hash" data-algorithm="<?= html($algorithm) ?>" data-csrf="<?= html($app->csrf()) ?>">
    <div class="hash-file-container">
        <input type="file" class="hash-file" title="The file to hash." placeholder="Choose a file to hash..." />
        <button class="hash-generate" title="Generate the <?= html($algorithm) ?> hash for the file."><span class="fa-solid fa-upload"></span>&nbsp;Upload</button>
    </div>

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

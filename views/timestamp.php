<?php

/**
 * @var $app WebApplication The WebApplication instance.
 * @var $timestamp int|null The timestamp.
 * @var $year int|null The year for the date.
 * @var $month int|null The month for the date.
 * @var $day int|null The day for the date.
 * @var $hour int|null The hour for the date.
 * @var $minute int|null The minute for the date.
 * @var $second int|null The second for the date.
 */

use Equit\View;
use Equit\WebApplication;

View::layout("layouts.main");
?>

<?php View::push("scripts"); ?>
<script type="module" src="/scripts/Timestamp.js"></script>
<?php View::endPush(); ?>

<?php View::section("head-title"); ?>
Unix timestamps
<?php
View::endSection();
View::section("page-title");
?>
Unix timestamps
<?php View::endSection(); ?>

<?php View::section("content"); ?>
<div class="timestamp-container">
    <span>
        <input type="number" class="timestamp-timestamp" value="<?= $timestamp ?? 0 ?>" title="Enter a Unix timestamp to convert." placeholder="Unix timestamp..."/>
        <button type="button" class="timestamp-reset" title="Clear the timestamp."><span class="fa-solid fa-delete-left"></span></button>
        <button type="button" class="timestamp-now" title="Get the timestamp for the current time."><span class="fa-solid fa-clock"></span></button>
        <input type="checkbox" class="timestamp-updated" title="Keep the timestamp up-to-date." />&nbsp;Keep updated
        <button type="button" class="timestamp-copy" title="Copy the timestamp to the clipboard."><span class="fa-solid fa-copy"></span></button>
        </span>
    <span>
        <input type="number" class="timestamp-date timestamp-day" value="<?= $day ?? 1 ?>" title="Day to convert to a Unix timestamp." placeholder="Day..." min="1" max="31" />
        <select size="1" class="timestamp-date timestamp-month" title="Month to convert to a Unix timestamp.">
            <option value="1" <?php if (1 === ($month ?? 1)): ?>selected="selected"<?php endif; ?>>January</option>
            <option value="2" <?php if (2 === ($month ?? 1)): ?>selected="selected"<?php endif; ?>>February</option>
            <option value="3" <?php if (3 === ($month ?? 1)): ?>selected="selected"<?php endif; ?>>March</option>
            <option value="4" <?php if (4 === ($month ?? 1)): ?>selected="selected"<?php endif; ?>>April</option>
            <option value="5" <?php if (5 === ($month ?? 1)): ?>selected="selected"<?php endif; ?>>May</option>
            <option value="6" <?php if (6 === ($month ?? 1)): ?>selected="selected"<?php endif; ?>>June</option>
            <option value="7" <?php if (7 === ($month ?? 1)): ?>selected="selected"<?php endif; ?>>July</option>
            <option value="8" <?php if (8 === ($month ?? 1)): ?>selected="selected"<?php endif; ?>>August</option>
            <option value="9" <?php if (9 === ($month ?? 1)): ?>selected="selected"<?php endif; ?>>September</option>
            <option value="10" <?php if (10 === ($month ?? 1)): ?>selected="selected"<?php endif; ?>>October</option>
            <option value="11" <?php if (11 === ($month ?? 1)): ?>selected="selected"<?php endif; ?>>November</option>
            <option value="12" <?php if (12 === ($month ?? 1)): ?>selected="selected"<?php endif; ?>>December</option>
        </select>
        <input type="number" class="timestamp-date timestamp-year" value="<?= $year ?? 1970 ?>" title="Year to convert to a Unix timestamp." placeholder="Year..." min="1970" max="9999" />
    </span>
    <span>
        <input type="number" class="timestamp-time timestamp-hour" value="<?= $hour ?? 0 ?>" title="Hour to convert to a Unix timestamp." placeholder="Hour..." min="0" max="23" /> :
        <input type="number" class="timestamp-time timestamp-minute" value="<?= $minute ?? 0 ?>" title="Minute to convert to a Unix timestamp." placeholder="Minute..." min="0" max="59" /> :
        <input type="number" class="timestamp-time timestamp-second" value="<?= $second ?? 0 ?>" title="Second to convert to a Unix timestamp." placeholder="Second..." min="0" max="59" />
    </span>
</div>
<?php View::endSection(); ?>

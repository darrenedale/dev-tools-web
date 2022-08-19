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
<script type="module" src="/scripts/RegexTester.js"></script>
<?php View::endPush(); ?>

<?php View::section("head-title"); ?>
Regex Tester
<?php
View::endSection();
View::section("page-title");
?>
Regex Tester
<?php View::endSection(); ?>

<?php View::section("content"); ?>
<div class="regex-tester">
    <div class="regex-input-container">
        <input class="regex" type="text" placeholder="Regular expression..." title="Enter a regular expression to test."/>
        <button class="regex-copy" title="Copy this regular expression to clipboard."><span class="fa-regular fa-copy"></span></button>
    </div>
    <div class="regex-test-string-container">
        <input class="test-string" type="text" placeholder="Test text..." title="Enter some text to test the regular expression..."/>
        <span class="result fa-solid fa-check"></span>
        <button class="test-string-copy" title="Copy this test text to clipboard."><span class="fa-regular fa-copy"></span></button>
    </div>
</div>
<?php View::endSection(); ?>

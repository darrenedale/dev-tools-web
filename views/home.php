<?php

/**
 *
 */

use Bead\View;

View::layout("layouts.main");

View::section("head-title");
?>
Home
<?php
View::endSection();
View::section("page-title");
?>
Home
<?php View::endSection(); ?>

<?php View::section("content"); ?>
<p>Choose an item from the navigation.</p>
<?php View::endSection(); ?>

<?php

/**
 * @var Throwable $exception
 */

use Bead\View;

View::layout("layouts.main");

View::section("content");
?>
<ul>

<?php while ($error): ?>
<?= html($error->getMessage()); ?>
<?php
    $error = $error->getPrevious();
    endwhile;
?>

</ul>
<?php View::endSection(); ?>


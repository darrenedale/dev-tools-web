<?php

/**
 * @var $app \Equit\WebApplication
 *
 */

$messages = $app->sessionData("messages")["errors"] ?? null;

if (!empty($messages)):
?>
<ul>
    <?php foreach($app->sessionData("messages")["errors"] as $error): ?>
        <li><?= html($error) ?></li>
    <?php endforeach; ?>
</ul>
<?php endif; ?>
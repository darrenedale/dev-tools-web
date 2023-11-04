<!DOCTYPE html>
<?php
use Bead\View;
?>
<html lang="en-gb">
<head>
    <title>Useful Dev Tools</title>
    <?php View::stack("scripts"); ?>
    <link rel="stylesheet" href="/styles/dev-tools.css" />
    <link rel="stylesheet" href="/styles/fontawesome/css/regular.css" />
    <link rel="stylesheet" href="/styles/fontawesome/css/solid.css" />
    <link rel="stylesheet" href="/styles/fontawesome/css/brands.css" />
    <link rel="stylesheet" href="/styles/fontawesome/css/all.css" />
    <?php View::stack("styles"); ?>
</head>
<body>
<header>
    <h1><a href="/">Dev Tools</a></h1>
</header>

<nav class="main-nav">
    <?php View::include("includes.main-nav"); ?>
</nav>

<section class="main">
    <?php if (View::hasSection("page-title")): ?>
        <h2><?php View::yieldSection("page-title"); ?></h2>
    <?php endif; ?>

    <?php View::include("includes.messages") ?>
    <?php View::yieldSection("content"); ?>
</section>

<footer>

</footer>
</body>
</html>
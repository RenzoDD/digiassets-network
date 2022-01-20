<?php

if (!isset($pageName))
    $pageName = "";

?>

<navbar class="navbar navbar-expand-md navbar-dark bg-dark header">
    <div class="container">
        <a class="navbar-brand" href="/">
            <img src="/assets/img/logo-white.png" height="50">
            <span class="h5 align-middle"><b>DigiAssets Explorer</b></span>
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse text-center" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <?php
                Bootstrap::NavbarItem(Bootstrap::Icon("house-door-fill") . " Home",   true, "/",       str_starts_with($pageName, "/home"));
                Bootstrap::NavbarItem(Bootstrap::Icon("collection-fill") . " Assets", true, "/assets", str_starts_with($pageName, "/assets"));
                Bootstrap::NavbarItem(Bootstrap::Icon("flag-fill") . " About", true, "/about", str_starts_with($pageName, "/about"));
                ?>
            </ul>
        </div>
    </div>
</navbar>
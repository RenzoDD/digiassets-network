<?php
/*
 * Copyright 2021 (c) Renzo Diaz
 * Licensed under MIT License
 * Class controller
 */

require_once __MODEL__ . "/ClassModel.php";

class WebSiteController
{
    public static function Home()
    {
        $pageName = "/home";
        $title = "DigiAssets Explorer";
        $description = "Explore the DigiAssets Metaverse";

        require __VIEW__ . "/home.php";
    }
}

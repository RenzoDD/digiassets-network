<?php
/*
 * Copyright 2021 (c) Renzo Diaz
 * Licensed under MIT License
 * Class controller
 */

require_once __MODEL__ . "/DigiAssetModel.php";
require_once __MODEL__ . "/IpfsCidModel.php";

class WebSiteController
{
    public static function Home()
    {
        $pageName = "/home";
        $title = "DigiAssets Explorer";
        $description = "Explore the DigiAssets Metaverse";

        require __VIEW__ . "/home.php";
    }
    public static function Asset($assetID)
    {
        $digiAsset = new DigiAssetModel();
        $digiAsset = $digiAsset->ReadAssetID($assetID);

        if ($digiAsset != null) {
            $cids = new IpfsCidModel();
            $cids = $cids->ReadDigiAssetID($digiAsset->DigiAssetID);

            $pageName = "/asset";
            $title = "DigiAssets Explorer - $assetID";
            $description = "Check this DigiAsset!";

            require __VIEW__ . "/asset.php";
        } else {
            $cids = [];

            $pageName = "/missing";
            $title = "DigiAssets Explorer - Not found";
            $description = "Asset not found";

            require __VIEW__ . "/missing.php";
        }
    }
    public static function AssetsList($page, $cant)
    {
        $digiAssets = new DigiAssetModel();
        $pages = ceil($digiAssets->ReadQuantity() / $cant);
        $digiAssets = $digiAssets->ReadAll($page, $cant);

        $pageName = "/assets";
        $title = "DigiAssets Explorer - Asset List";
        $description = "List of DigiAssets";

        require __VIEW__ . "/assets.php";
    }
    public static function Address($address)
    {
        $pageName = "/address";
        $title = "DigiAssets Explorer - $address";
        $description = "Check this DigiByte Address";

        require __VIEW__ . "/address.php";
    }
}

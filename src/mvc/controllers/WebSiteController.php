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

            if ($digiAsset->Name != null)
                $title = "DigiAssets Explorer - $digiAsset->Name";
            else
                $title = "DigiAssets Explorer - $assetID";
            $description = "Check this DigiAsset!";

            if (sizeof($cids) > 0) {
                $keys = array_keys($cids);
                foreach ($keys as $key) {
                    if ($cids[$key]->Data != null) {
                        $json = json_decode($cids[$key]->Data);

                        if (isset($json->data) && isset($json->data->urls[0]) && isset($json->data->urls[0]->mimeType) && isset($json->data->urls[0]->url)) {
                            if (str_starts_with($json->data->urls[0]->mimeType, "image/")) {
                                if (str_starts_with($json->data->urls[0]->url, "ipfs://")) {
                                    $image = "https://ipfs.io/ipfs/" . substr($json->data->urls[0]->url, 7);
                                    break;
                                } else
                                    $image = $json->data->urls[0]->url;
                            }
                        }
                    }
                }
            }
            if (!isset($image))
                $image = "/assets/img/logo.png";

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
        $quantity = $digiAssets->ReadQuantity();
        $pages = ceil($quantity / $cant);
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
    public static function About()
    {
        $pageName = "/about";
        $title = "DigiAssets Explorer - About";
        $description = "The creator of the explorer";

        require __VIEW__ . "/about.php";
    }
}

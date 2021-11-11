<?php
/*
 * Copyright 2021 (c) Renzo Diaz
 * Licensed under MIT License
 * Index router
 */

require_once "mvc-init.php";
require_once "config.php";
session_start();

require_once __CONTROLLER__ . "/WebSiteController.php";

route("/", function () {
    WebSiteController::Home();
});
route("/assets/:page?", function () {
    $_GET["page"] = $_GET["page"] == null ? 1 : $_GET["page"];
    WebSiteController::AssetsList($_GET["page"], 10);
});
route("/asset/:asset", function () {
    WebSiteController::Asset($_GET["asset"]);
});
route("/address/:address", function () {
    WebSiteController::Address($_GET["address"]);
});
route("/sync", function () {
    $asset = new DigiAssetModel();
    $ipfs = new IpfsCidModel();

    $last = $asset->ReadLast();

    if ($last == null)
        $url = "https://ipfs.digiassetx.com/1";
    else
        $url = "https://ipfs.digiassetx.com/$last->Height";

    $data = (array)HTTP::Get($url);
    $ids = array_keys($data);

    if (in_array($last->AssetID, $ids) == false) {
        if ($ids[sizeof($ids) - 1] != $last->AssetID) {
            foreach ($ids as $key) {
                $asset->Create($key, $data[$key]->height);
                foreach ($data[$key]->cids as $cid) {
                    $ipfs->Create($asset->DigiAssetID, $cid);
                }
            }
        } else {
            echo "synced";
        }
    } else if ($ids[sizeof($ids) - 1] != $last->AssetID) {
        foreach ($ids as $key) {
            if ($asset->ReadAssetID($key) == null) {
                $asset->Create($key, $data[$key]->height);
            }
            foreach ($data[$key]->cids as $cid) {
                if ($ipfs->ReadCID($cid) == null) {
                    $ipfs->Create($asset->DigiAssetID, $cid);
                    //$info = HTTP::Get("https://ipfs.io/ipfs/$cid", false);
                    //$ipfs->UpdateData($ipfs->IpfsCidID, $info);
                }
            }
        }
    } else {
        echo "synced";
    }
});

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
    $data = (array)HTTP::Get("https://ipfs.digiassetx.com/1");

    $ids = array_keys($data);

    $asset = new DigiAssetModel();
    $ipfs = new IpfsCidModel();

    foreach ($ids as $key) {
        if ($asset->Create($key, $data[$key]->height)); {
            foreach ($data[$key]->cids as $cid) {
                $ipfs->Create($asset->DigiAssetID, $cid);
            }
        }
    }

    $data = (array)HTTP::Get("https://ipfs.digiassetx.com/" . $data[$key]->height);
    $ids = array_keys($data);
    foreach ($ids as $key) {
        if ($asset->Create($key, $data[$key]->height)); {
            foreach ($data[$key]->cids as $cid) {
                $ipfs->Create($asset->DigiAssetID, $cid);
            }
        }
    }

    $data = (array)HTTP::Get("https://ipfs.digiassetx.com/" . $data[$key]->height);
    $ids = array_keys($data);
    foreach ($ids as $key) {
        if ($asset->Create($key, $data[$key]->height)); {
            foreach ($data[$key]->cids as $cid) {
                $ipfs->Create($asset->DigiAssetID, $cid);
            }
        }
    }

    $data = (array)HTTP::Get("https://ipfs.digiassetx.com/" . $data[$key]->height);
    $ids = array_keys($data);
    foreach ($ids as $key) {
        if ($asset->Create($key, $data[$key]->height)); {
            foreach ($data[$key]->cids as $cid) {
                $ipfs->Create($asset->DigiAssetID, $cid);
            }
        }
    }

    $data = (array)HTTP::Get("https://ipfs.digiassetx.com/" . $data[$key]->height);
    $ids = array_keys($data);
    foreach ($ids as $key) {
        if ($asset->Create($key, $data[$key]->height)); {
            foreach ($data[$key]->cids as $cid) {
                $ipfs->Create($asset->DigiAssetID, $cid);
            }
        }
    }

    $data = (array)HTTP::Get("https://ipfs.digiassetx.com/" . $data[$key]->height);
    $ids = array_keys($data);
    foreach ($ids as $key) {
        if ($asset->Create($key, $data[$key]->height)); {
            foreach ($data[$key]->cids as $cid) {
                $ipfs->Create($asset->DigiAssetID, $cid);
            }
        }
    }

    $data = (array)HTTP::Get("https://ipfs.digiassetx.com/" . $data[$key]->height);
    $ids = array_keys($data);
    foreach ($ids as $key) {
        if ($asset->Create($key, $data[$key]->height)); {
            foreach ($data[$key]->cids as $cid) {
                $ipfs->Create($asset->DigiAssetID, $cid);
            }
        }
    }

    $data = (array)HTTP::Get("https://ipfs.digiassetx.com/" . $data[$key]->height);
    $ids = array_keys($data);
    foreach ($ids as $key) {
        if ($asset->Create($key, $data[$key]->height)); {
            foreach ($data[$key]->cids as $cid) {
                $ipfs->Create($asset->DigiAssetID, $cid);
            }
        }
    }

    $data = (array)HTTP::Get("https://ipfs.digiassetx.com/" . $data[$key]->height);
    $ids = array_keys($data);
    foreach ($ids as $key) {
        if ($asset->Create($key, $data[$key]->height)); {
            foreach ($data[$key]->cids as $cid) {
                $ipfs->Create($asset->DigiAssetID, $cid);
            }
        }
    }
});

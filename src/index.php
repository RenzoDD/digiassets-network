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
route("/about", function () {
    WebSiteController::About();
});
route("/assets/img/addresses/:data", function () {
    $data = "digibyte:" . substr($_GET["data"], 0, -4);
    $filepath = __IMG__ . "/addresses/" . $_GET["data"];
    $logopath = __IMG__ . "/digibyte.png";

    QR::Create($data, $filepath, $logopath);

    header('Content-Type: image/png');
    header('Content-Length: ' . filesize($filepath));
    readfile($filepath);
    return;
});

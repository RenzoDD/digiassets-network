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

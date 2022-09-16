<?php

define("SERVER_PORT", (!empty($_SERVER["SERVER_PORT"]) ? ":" . $_SERVER["SERVER_PORT"] : ""));
define("SITE_URL", $_SERVER["REQUEST_SCHEME"] . "://" . $_SERVER["SERVER_NAME"] . SERVER_PORT);
define("BASE_DIR", dirname(__DIR__));



const APP_DIR = BASE_DIR . "/App";
const VIEW_DIR = APP_DIR . "/Views/";
const ASSET_URL = SITE_URL . "/assets";
const IMG_URL = ASSET_URL . "/images";
const JS_URL = ASSET_URL . "/js";
const CSS_URL = ASSET_URL . "/css";
const FONTS_URL = ASSET_URL . "/fonts";
const LIBS_URL = ASSET_URL . "/libs";
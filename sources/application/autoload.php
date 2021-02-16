<?php

defined("APP_PATH")  || define("APP_PATH", realpath(dirname(__FILE__)));
defined("ROOT_PATH") || define("ROOT_PATH",  APP_PATH . "/..");
defined("CONF_PATH") || define("CONF_PATH", ROOT_PATH . "/conf");
defined("LOG_PATH")  || define("LOG_PATH", ROOT_PATH . "/logs");
defined("APP_ENV")   || define("APP_ENV", getenv("APP_ENV") ?? "dev");

require_once (APP_PATH . "/Log/Logger.php");
require_once (APP_PATH . "/Loader/Loader.php");
require_once (APP_PATH . "/Conf/Config.php");
require_once (APP_PATH . "/Adapter/Adapter.php");
require_once (APP_PATH . "/Core.php");



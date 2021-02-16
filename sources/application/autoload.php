<?php

$systemClassNames = ['Adapter', 'Conf', 'Loader', 'Log'];
defined("APP_PATH")  || define("APP_PATH", realpath(dirname(__FILE__)));
defined("ROOT_PATH") || define("ROOT_PATH",  APP_PATH . "/..");
defined("CONF_PATH") || define("CONF_PATH", ROOT_PATH . "/conf");
defined("LOG_PATH")  || define("LOG_PATH", ROOT_PATH . "/logs");
defined("APP_ENV")   || define("APP_ENV", getenv("APP_ENV") ?? "dev");

foreach ($systemClassNames as $systemClassName){
    spl_autoload_register(function (string $systemClassName) {
        require_once __DIR__ . '/application/' . $systemClassName . '/' . $systemClassName . '.php';
    });
    var_dump(__DIR__ . '/application/' . $systemClassName . '/' . $systemClassName . '.php');
}


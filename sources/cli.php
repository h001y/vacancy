<?php

ini_set("display_errors",1); // Вывод ошибок
error_reporting  (E_ALL); // Вывод ошибок

require __DIR__.'/application/autoload.php';

$argv = $_SERVER["argv"];

if (count($argv) > 0) {
	$core = new Application\Core();
	$loader = $core->getLoader();
	$loader->load($argv[1]);
} else {
	echo "USE: cli.php %path/to/file%";
}



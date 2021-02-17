<?php

namespace Application;

use Application\Log\Logger;
use Application\Loader\Loader;

class Core
{

	private $loader;

    const ROOT_PATH = __DIR__ . "/..";
    const LOG_PATH = __DIR__  . "/../logs";
    const CONF_PATH = __DIR__ . "/../conf";

	public function __construct()
    {
		Logger::getInst()->info("Core instance created");
	}

	/**
	 * @return Loader
	 */
	public function getLoader()
    {
		Logger::getInst()->info("Core::getLoader");
		if (!isset($this->loader)) {
			$this->createLoader();
		}

		return $this->loader;
	}

	private function createLoader()
    {
		Logger::getInst()->info("Core::createLoader");
		$this->loader = new Loader();
	}
}
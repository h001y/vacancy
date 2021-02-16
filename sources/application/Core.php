<?php

namespace Application;

use Application\Log\Logger;
use Application\Loader\Loader;

class Core
{

	private $loader;

	const ROOT_PATH = APP_PATH . "/..";
	public function __construct()
    {
		Log::getInst()->info("Core instance created");
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
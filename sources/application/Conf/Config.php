<?php

namespace Application\Conf;

class Config {

	protected $conf;
	protected static $inst;

	const CONF_PATH = ROOT_PATH . "/conf";
	const APP_ENV = "dev";

	private function __construct()
    {
		$conf = parse_ini_file(CONF_PATH . "/conf_" . APP_ENV . ".ini", true);
		$conf = array_map( function($section){
			return (object) $section;
		}, $conf);

		$this->conf = (object) $conf;
	}

	public static function getInst()
    {
		if (!isset(self::$inst)){
			self::$inst = new self();
		}

		return self::$inst;
	}

	public function getConf()
    {
		return $this->conf;
	}

}
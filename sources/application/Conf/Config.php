<?php

namespace Application\Conf;

class Config {

	protected $conf;
	protected static $inst;

	private function __construct()
    {
		$conf = parse_ini_file(CONF_PATH . "/conf_" . APP_ENV . ".ini", true);
		$conf = array_map( function($section){
			return (object) $section;
		}, $conf);

		$this->conf = (object) $conf;
	}

    /**
     * Singleton pattern
     * @return Config
     */
    public static function getInst(): Config
    {
		if (!isset(self::$inst)){
			self::$inst = new self();
		}

		return self::$inst;
	}

    /**
     * Get conf from .ini
     * @return object
     */
    public function getConf(): object
    {
		return $this->conf;
	}

}
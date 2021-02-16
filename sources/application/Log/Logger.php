<?php

namespace Application\Log;

use Application\Conf\Config as Conf;
use Application\Core;

class Logger
{

	const LEVEL_DEBUG = "DEBUG";
	const LEVEL_INFO = "INFO";
	const LEVEL_WARN = "WARN";
	const LEVEL_ERROR= "ERROR";

	const LOG_PATH = Core::ROOT_PATH . "/logs";

	private static $inst;
	private $logFile;
	private $logFileResource;

	private function __construct()
    {
		$this->logFile = $this::LOG_PATH . "/log.txt";
	}

	public static function getInst()
    {
		if (!isset(self::$inst)){
			self::$inst = new self();
		}

		return self::$inst;
	}

	public function debug($message)
    {
		$conf = Conf::getInst()->getConf();
		if ($conf->logger->log_level === "debug") {
			$this->writeMessage($message, self::LEVEL_DEBUG);
		}
	}

	public function info($message)
    {
	    return $this->writeMessage($message);
	}

	public function warn($message)
    {
		return $this->writeMessage($message, self::LEVEL_WARN);
	}

	private function writeMessage($message, $logLevel = self::LEVEL_INFO) {
		$currDate = date("d-m-Y h:i:s");
		$message = "[$logLevel]" . "[$currDate]: " .  $message . PHP_EOL;
        return file_put_contents($this->logFile, $message, FILE_APPEND);
	}
}
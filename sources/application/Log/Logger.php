<?php

namespace Application\Log;

use Application\Conf\Config as Conf;

class Logger
{

	const LEVEL_DEBUG = "DEBUG";
	const LEVEL_INFO = "INFO";
	const LEVEL_WARN = "WARN";
	const LEVEL_ERROR = "ERROR";
	const LOG_PATH = __DIR__."/../../logs";

	private static $inst;

    private function __construct()
    {
		$this->logFile = $this::LOG_PATH . "/log.txt";
	}


    /**
     * Singleton pattern
     * @return Logger
     */
    public static function getInst(): Logger
    {
		if (!isset(self::$inst)){
			self::$inst = new self();
		}

		return self::$inst;
	}

    /**
     * logging debug
     * @param $message
     */
    public function debug($message) : bool
    {
		$conf = Conf::getInst()->getConf();
		if ($conf->logger->log_level === "debug") {
			return $this->writeMessage($message, self::LEVEL_DEBUG);
		}
	}

    /**
     * Write info in log
     * @param $message
     * @return bool
     */
    public function info($message) : bool
    {
	    return $this->writeMessage($message, self::LEVEL_INFO);
	}

    /**
     * Write info in log
     * @param $message
     * @return bool
     */
    public function error($message): bool
    {
        return $this->writeMessage($message, self::LEVEL_ERROR);
    }

    /**
     * Write warning message in log
     * @param $message
     * @return bool
     */
	public function warn($message) : bool
    {
		return $this->writeMessage($message, self::LEVEL_WARN);
	}

    /**
     * Write message in log default - INFO message
     * example : '[INFO][17-02-2021 08:43:43]: Connection successfully'
     * @param $message
     * @param string $logLevel
     * @return bool
     */
	private function writeMessage($message, $logLevel = self::LEVEL_INFO) : bool
    {
		$currDate = date("d-m-Y h:i:s");
		$message = "[$logLevel]" . "[$currDate]: " .  $message . PHP_EOL;
        return !empty(file_put_contents($this->logFile, $message, FILE_APPEND));
	}
}
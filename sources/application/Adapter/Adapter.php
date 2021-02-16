<?php

namespace Application\Adapter;

use Application\Conf\Config as Conf;
use Application\Log\Logger;

class Adapter {

	private static $inst;

	/**
	 * @var \PDO
	 */
	private $connection;

	private function __construct(){}

	public static function getInst()
    {
		if (!isset(self::$inst)){
			self::$inst = new self();
		}

		return self::$inst;
	}

	public function getConnection()
    {
		$conf = Conf::getInst()->getConf();
		$this->connection = new \PDO("mysql:host={$conf->db->host};dbname={$conf->db->name}", $conf->db->user, $conf->db->password);
	}

	public function dropConnection()
    {
		if (isset($this->connection)) {
			$this->connection = null;
		}
	}

	public function fetch($query)
    {
        try {
            $result = [];
            $this->getConnection();
            $this->connection->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
            $stmt = $this->connection->prepare($query);
            $stmt->execute();
            $result = $stmt->fetchAll();

            $this->dropConnection();
            return $result;
        }  catch (PDOException $e) {
            Logger::getInst()->debug("Error is thrown with message - " . $e->getMessage());
        }
    }

	public function exec($query)
    {
		try {
			$this->getConnection();
			$this->connection->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
			$stmt = $this->connection->prepare($query);
			$stmt->execute();
			$this->dropConnection();
		} catch (PDOException $e) {
			Logger::getInst()->debug("Error is thrown with message - " . $e->getMessage());
		}
	}

}
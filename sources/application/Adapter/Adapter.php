<?php

namespace Application\Adapter;

use Application\Conf\Config as Conf;
use Application\Log\Logger;
use PDO;
use PDOException;

class Adapter {

	private static $inst;

	/**
	 * @var PDO
	 */
	private $connection;

	private function __construct(){}

    /**
     * Singleton pattern
     * @return Adapter
     */
    public static function getInst(): Adapter
    {
		if (!isset(self::$inst)){
			self::$inst = new self();
		}

		return self::$inst;
	}

    /**
     * Create connect to DB with PDO driver
     *
     */
    public function getConnection() : PDO
    {
        try {
            $conf = Conf::getInst()->getConf();
             $this->connection = new PDO("mysql:host={$conf->db->host};dbname={$conf->db->name}", $conf->db->user, $conf->db->password);
        } catch (PDOException $e) {
            Logger::getInst()->error("Error connection to database - " . $e->getMessage());
        }
        return $this->connection;
    }


    /**
     * Drop connection
     */
    public function dropConnection() : PDO
    {
			return $this->connection = null ?? $this->connection;
	}

    /**
     * Get data with fetchALl
     * @param $query
     * @return array
     */
    public function fetch($query): array
    {
        try {
                $result = [];
                $this->getConnection();
                $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                $stmt = $this->connection->prepare($query);
                $stmt->execute();
                $result = $stmt->fetchAll();
                $this->dropConnection();
        }  catch (PDOException $e) {
            Logger::getInst()->debug("Error is thrown with message - " . $e->getMessage());
        }
        return $result;
    }

    /**
     * Exec with PDO
     * @param $query
     * @return bool
     */
    public function exec($query) : bool
    {
        $result = false;
		try {
			$this->getConnection();
			$this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			$stmt = $this->connection->prepare($query);
			$result = $stmt->execute();
			$this->dropConnection();
		} catch (PDOException $e) {
			Logger::getInst()->debug("Error is thrown with message - " . $e->getMessage());
		}
		return $result;
	}

}
<?php

namespace Application\Loader;

require_once (APP_PATH . "/Adapter/Adapter.php");
require_once (APP_PATH . "/Log/Logger.php");

use Application\Log\Logger as Logger;
use Application\Adapter\Adapter;

class Loader {

	public function __construct(){}

	public function load($file)
    {
		Logger::getInst()->info("Starting to load file $file");
		$handle = fopen($file, "r");
		$fileContent = [];
		while (($data = fgetcsv($handle, "1000", ",")) !== false) {
			$fileContent[] = $data;
		}
		unset($fileContent[0]);
		$this->parse($fileContent);

		Logger::getInst()->info("File load is finished");
	}

	private function parse($content)
    {
		Logger::getInst()->info("Starting to parse file");
		$needleFields = [0, 3, 5];
		foreach ($content as $k=>$string) {
		    foreach($needleFields as $field){
		        $query_param[$k][] = $string[$field];
            }
		    $query_param[$k][] =  date('Y-m-d H:i:s');
        }

        $query = 'INSERT 
                                INTO `market_data` (id_value, price, is_noon, update_date) 
                                VALUES (' . implode(', ', $query_param[$k]) .')';
        Adapter::getInst()->exec($query);
			var_dump($query_param);
			die();

		Logger::getInst()->info("File parsing is finished");
	}

}

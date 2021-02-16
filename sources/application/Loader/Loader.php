<?php

namespace Application\Loader;

use Application\Log\Logger;
use Application\Adapter\Adapter;

class Loader
{

	public function __construct(){}

	private function getRegion(string $file): string
    {
        $filePath = explode('/', $file);
        $filename = array_pop($filePath);
        $partFilenames = explode('.', $filename);
        $regionTypes = ['eu', 'us'];
        $currentRegion = '';
        $result = '';
        foreach($partFilenames as $partFilename){
            if (in_array($partFilename, $regionTypes)) {
                $result = $partFilename;
            }
        }
        return $result;
    }

	public function load(string $file)
    {
		Logger::getInst()->info("Starting to load file $file");
		$currentRegion = $this->getRegion($file);
		$handle = fopen($file, "r");
		$fileContent = [];
		while (($data = fgetcsv($handle, "1000", ",")) !== false) {
			$fileContent[] = $data;
		}
		unset($fileContent[0]);
		$this->parse($fileContent, $currentRegion);

		Logger::getInst()->info("File load is finished");
	}

	private function parse(array $content, string $region)
        {
        Logger::getInst()->info("Starting to parse file");

        $needleFields = [0, 1, 5];
        foreach ($content as $entry){
            $fieldsToInsert = [];

            foreach($entry as $index=>$entryField){
                if (in_array($index, $needleFields)) {
                    $fieldsToInsert[] = '"' . $entryField. '"';
                }
            }

            $fieldsToInsert[] =  '"' . date('Y-m-d H:i:s') . '"';

            $query = 'INSERT INTO `market_data` (id_value, price, is_noon, update_date) 
                             VALUES (' . implode(", ", $fieldsToInsert). ' )';
            Adapter::getInst()->exec($query, $fieldsToInsert);
        }
        Logger::getInst()->info("File parsing is finished");
    }

}

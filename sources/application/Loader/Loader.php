<?php

namespace Application\Loader;

use Application\Log\Logger;
use Application\Adapter\Adapter;

class Loader
{

	public function __construct(){}

	private function getRegion(array $partFilenames): string
    {
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

    private function getDateFromFilename(array $partFilenames): string
    {
        return array_pop($partFilenames);
    }

    private function getFileParts(string $file): array
    {
        $filePath = explode('/', $file);
        $filename = array_pop($filePath);
        $partFilenames = explode('.', $filename);
        return $partFilenames;
    }

	public function load(string $file)
    {
		Logger::getInst()->info("Starting to load file $file");
        $partFilenames = $this->getFileParts($file);
        $currentRegion = $this->getRegion($partFilenames);
        $dateFile = $this->getDateFromFilename($partFilenames);
		$handle = fopen($file, "r");
		$fileContent = [];
		while (($data = fgetcsv($handle, "1000", ",")) !== false) {
			$fileContent[] = $data;
		}
		unset($fileContent[0]);
		$this->parse($fileContent, $currentRegion, $dateFile);

		Logger::getInst()->info("File load is finished");
	}

	private function parse(array $content, string $region = 'eu', string $dateFile = '')
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

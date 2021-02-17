<?php

namespace Application\Loader;

use Application\Log\Logger;
use Application\Adapter\Adapter;

class Loader
{

    private static $inst;

    public function __construct(){}

    /**
     * Get region abbreviation from filename
     * @param array $partFilenames
     * @return string
     */
    private function getRegion(array $partFilenames): string
    {
        $regionTypes = ['eu', 'us'];
        $result = '';
        foreach($partFilenames as $partFilename){
            if (in_array($partFilename, $regionTypes)) {
                $result = $partFilename;
            }
        }
        return $result;
    }

    /**
     * Get data date from filename
     * @param array $partFilenames
     * @return string
     */
    private function getDateFromFile(array $partFilenames): string
    {
        return array_pop($partFilenames);
    }

    /**
     * Get parts of file path
     * @param string $file
     * @return array
     */
    private function getFileParts(string $file): array
    {
        $filePath = explode('/', $file);
        $filename = array_pop($filePath);
        return explode('.', $filename);
    }

    /**
     * Load file to array
     * @param string $file
     */
    public function load(string $file)
    {
		Logger::getInst()->info("Starting to load file $file");
        $partFilenames = $this->getFileParts($file);
        $currentRegion = $this->getRegion($partFilenames);
        $dateFile = $this->getDateFromFile($partFilenames);
		$handle = fopen($file, "r");
		$fileContent = [];
		while (($data = fgetcsv($handle, "1000", ",")) !== false) {
			$fileContent[] = $data;
		}
		unset($fileContent[0]);
		$this->parse($fileContent, $currentRegion, $dateFile);

		Logger::getInst()->info("File load is finished");
	}

    /**
     * Validate table markets id_value with file[0] id_value
     * @param string $idValue
     * @return bool
     */
    public function isValidate(string $idValue) : bool
    {
        $query = 'SELECT * 
                  FROM `markets` WHERE id_value = ' . $idValue;

       return !empty(Adapter::getInst()->fetch($query));
    }

	private function parse(array $content, string $region = 'eu', string $dateFile = '')
        {
        Logger::getInst()->info("Starting to parse file");
        $pricePosition = 1;
        $insertedCount = 0;
        $idValuePosition = 0;
            switch ($region) {
                case 'eu':
                    $stringToAdd = 'id_value, price, is_noon, update_date';
                    $needleFields[] = 0;
                    $idValuePosition = 0;
                    break;
                case 'us':
                    $stringToAdd = 'price, is_noon, id_value, update_date';
                    $needleFields[] = 6;
                    $idValuePosition = 2;
                    break;
            }

        array_push($needleFields, 1, 5);

        foreach ($content as $entry){
            $fieldsToInsert = [];

            foreach($entry as $index=>$entryField){
                if (in_array($index, $needleFields)) {
                    if($index == '1'){
                        $fieldsToInsert[] = '"' . substr($entryField, 3). '"';
                    } else {
                        $fieldsToInsert[] = '"' . $entryField. '"';
                    }
                }
            }

            $dateFileTs = strtotime($dateFile);
            $fieldsToInsert[] =  '"' . date('Y-m-d', $dateFileTs) . '"';
            if ($this->isValidate($fieldsToInsert[$idValuePosition])) {
                $query = 'INSERT INTO `market_data` (' . $stringToAdd . ') 
                             VALUES (' . implode(", ", $fieldsToInsert). ' )';
                Adapter::getInst()->exec($query);
                $insertedCount ++;
            }
        }
        Logger::getInst()->info("File parsing is finished. Added " . $insertedCount . " rows");
    }

}

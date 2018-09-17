<?php

namespace HyveMobileTest\Support;

use HyveMobileTest\Models\Contact;

class ContactImport {

	/** Parses contacts from a csv
	 * @param  [String] location of file to parse
	 * @return [Array] Array of contacts
	 */
	public function parseFromCSV($file, $offset = false, $limit = false) : array
	{
		$results = [];		
		//If a limit and or offset has been set get the appropriate data
		if ($offset || $limit) {
			$data = $this->getSpecificDataFromCSV($file, $offset, $limit);
			if ($offset === 0) {
				//Remove headers
				array_shift($data);
			}
		} else {
			$data = array_map('str_getcsv', file($file));
			//Remove headers
			array_shift($data);
		}
		$iter = new \ArrayIterator($data);
		foreach ($iter as $value) {
			$results[] = new Contact($value[1], $value[2], $value[3], $value[4], $value[5], $value[6], $value[7], $value[8]);
		}
		return $results;
	}

	/** Reads certain lines from the csv file
	 * @param  [String] $file [file location]
	 * @param  [int] $offset [number of lines to skip]
	 * @param  [int] $limit [number of lines to return]
	 * @return [array] $result [returns the result]
	 */
	private function getSpecificDataFromCSV($file, $offset = 0, $limit = 100000)
	{
		$results = [];
		if (($handle = fopen($file, 'r')) !== FALSE) {
			for ($i=0; $i < $offset; $i++) { 
				fgets($handle);
			}
			$row = 1;
			while (($data = fgetcsv($handle, 1024, ",")) !== FALSE && $row <= $limit) {
				$results[] = $data;
				$row++;
		    }
		    fclose($handle);
		}

		return $results;
	}
}
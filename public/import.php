<?php

/**
 * All db insert functionality below could be sent onto a queue that is handled by a different server
 */

use GuzzleHttp\Client as Guzzle;
use Predis\Client as PredisClient;
use HyveMobileTest\Support\Database;
use HyveMobileTest\Support\ContactImport;

require '../boot.php';

$extractFile = RESOURCEPATH.'data.csv.zip';
$extractFolder = RESOURCEPATH.'temp/';

if (!file_exists($extractFile)) {
	die('Could not find file to extract');
}

$zip = new ZipArchive();
if ($zip->open($extractFile) === TRUE) {
    $zip->extractTo('../resources/temp/');
    $zip->close();
} else {
    die('Could not find open file to extract');
}
if (!file_exists($extractFolder . '/MOCK_DATA.csv')) {
	die('Could not find file to parse');
}
$contactData = new ContactImport;
$importData = $contactData->parseFromCSV($extractFolder . '/MOCK_DATA.csv');
$postableData = [];
if (!empty($importData)) {
	$db = new Database;
	$client = new PredisClient();
	$iter = new \ArrayIterator($importData);
	foreach ($iter as $key => &$contact) {
		$domain = str_replace('@', '', strstr($contact->email, '@'));
		$ip = $client->get('domain['.$domain.']');

		if (is_null($ip)) {
			$ip = gethostbyname($domain);
			if ($ip === $domain) {
				$ip = '';
			}

			$client->set('domain['.$domain.']', $ip);
		}

		$contact->domain_ip = $ip;

		try {
			$db->insert('contacts', (array) $contact);
			$postableData[] = $contact;
		} catch (Exception $e) {
			/*Typically log any db errors here.
			 We will delete the generated images */
			unlink($contact->contact_card);
		}
	}
	if (count($postableData) > 0) {
		$guzzle = new Guzzle;
		$response = $guzzle->request('POST', 'http://example.org', [
		    'json' => $postableData
		]);
		if (!$response->getStatusCode() === 200) {
			die('Done');
		} else {
			die($response->getReasonPhrase());
		}
	}

	die('There where no new contacts.');
}
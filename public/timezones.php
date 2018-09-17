<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
require '../boot.php';

use Predis\Client as PredisClient;
use Illuminate\Database\Capsule\Manager as Capsule;

$timezone = $_GET['timezone'];
$page = isset($_GET['page']) ? $_GET['page'] : 1;

$limit = 15;

$offset = ($page - 1) * $limit;

$client = new PredisClient();

$timezone_data = json_decode($client->get('timezone.'.$timezone));
if (is_null($timezone_data)) {
	$timezone_data = Capsule::table('contacts')->where('tz', $timezone)->get()->toArray();
	$client->set('timezone.'.$timezone, json_encode($timezone_data));
	$client->expireat('timezone.'.$timezone, strtotime('+1 day'));
}
$contacts = array_slice($timezone_data, $offset, $limit);
$total_contacts = $client->get('timezone.'.$timezone.'.count');

if (is_null($total_contacts)) {
	$total_contacts = Capsule::table('contacts')->where('tz', $timezone)->count();
	$client->set('timezone.'.$timezone.'.count', $total_contacts);
	$client->expireat('timezone.'.$timezone.'.count', strtotime('+1 day'));
}

$next_page = null;
$prev_page = null;
if ($limit * $offset < $total_contacts) {
	$next_page_no = $page+1;
	$next_page = '/?page='.$next_page_no.'&timezone='.$timezone;
	
}
if ($page > 1) {
	$max_pages = ceil($total_contacts / $limit);
	$prev_page_no = $page-1;
	$prev_page = '/?page='.($prev_page_no > $max_pages ? $max_pages : $prev_page_no).'&timezone='.$timezone;
}

$results = compact('timezone', 'total_contacts', 'contacts', 'next_page', 'prev_page');
echo json_encode($results);
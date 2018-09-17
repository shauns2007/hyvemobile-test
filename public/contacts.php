<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
require '../boot.php';

use Predis\Client as PredisClient;
use Illuminate\Database\Capsule\Manager as Capsule;

$page = isset($_GET['page']) ? $_GET['page'] : 1;

$limit = 15;

$offset = ($page - 1) * $limit;

$client = new PredisClient();

$data = json_decode($client->get('contacts'));
if (is_null($data)) {
	$data = Capsule::table('contacts')->get()->toArray();
	$client->set('contacts', json_encode($data));
	$client->expireat('contacts', strtotime('+1 day'));
}

$contacts = array_slice($data, $offset, $limit);
$total_contacts = $client->get('contact_count');

if (is_null($total_contacts)) {
	$total_contacts = Capsule::table('contacts')->count();
	$client->set('contact_count', $total_contacts);
	$client->expireat('contact_count', strtotime('+1 day'));
}
$next_page = null;
$prev_page = null;
if ($limit * $offset < $total_contacts) {
	$next_page_no = $page+1;
	$next_page = '/?page='.$next_page_no;
	
}
if ($page > 1) {
	$max_pages = ceil($total_contacts / $limit);
	$prev_page_no = $page-1;
	$prev_page = '/?page='.($prev_page_no > $max_pages ? $max_pages : $prev_page_no);
}

$results = compact('contacts', 'next_page', 'prev_page');
echo json_encode($results);
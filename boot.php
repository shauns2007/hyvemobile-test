<?php

require_once __DIR__ . '/vendor/autoload.php';

use Illuminate\Database\Capsule\Manager as Capsule;

!defined('BASEPATH') ? define('BASEPATH', __DIR__) : '';
!defined('RESOURCEPATH') ? define('RESOURCEPATH', __DIR__ . '\resources\\') : '';

if (!file_exists(RESOURCEPATH.'images\\')) {
	mkdir(RESOURCEPATH.'images\\', 664);
}

if (!file_exists(RESOURCEPATH.'temp\\')) {
	mkdir(RESOURCEPATH.'temp\\', 664);
}

$capsule = new Capsule;

$capsule->addConnection([
    'driver'    => 'mysql',
    'host'      => 'localhost',
    'database'  => 'hyve',
    'username'  => 'root',
    'password'  => '',
    'charset'   => 'utf8',
    'collation' => 'utf8_unicode_ci',
    'prefix'    => '',
]);

$capsule->setAsGlobal();
$capsule->bootEloquent();
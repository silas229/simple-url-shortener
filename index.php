<?php
require 'vendor/autoload.php';

use App\Config;
use App\DB\Connection;
use App\DB\Tables;
use App\URLs;
use App\Log;

$pdo = (new Connection())->connect();
$tables = new Tables($pdo);
if ($tables->check_setup_permission()) header("Location: ./backend/setup.php");
//$tables->create();

$code = $_SERVER['REQUEST_URI'];
$code = 'ce421502dcb6';
if ($url = (new URLs($pdo))->get_single($code)) {
	$referrer = $_SERVER['HTTP_REFERER'];
	$useragent = $_SERVER['HTTP_USER_AGENT'];

	(new Log($pdo))->insert_click($code, $referrer, $useragent);
	header('Location: ' . $url);
} else {
	echo 'not found';
}

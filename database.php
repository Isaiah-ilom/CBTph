<?php
ob_start();
session_start();
ini_set('display_errors', 'On');
require_once ("inc/functions.php");

date_default_timezone_set('Africa/Lagos');

$protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http';
$host = isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : 'localhost:5000';
$base_url = $protocol . '://' . $host . '/';

define("ROOT_URL", $base_url);
define("ADMIN_ROOT_URL", $base_url . "admin/");
define("REQUEST_ROOT", "/");

$c_time = date('H:i:s', time());
$current_time = toSeconds($c_time);

function toSeconds($time) {
    $time = preg_replace("/^([d]{1,2}):([d]{2})$/", "00:$1:$2", $time);
    sscanf($time, "%d:%d:%d", $hours, $minutes, $seconds);
    return $hours * 3600 + $minutes * 60 + $seconds;
}

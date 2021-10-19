<?php
// заголовки
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

include_once '../../config/DB.php';
include_once '../../objects/User.php';

// Autoloader
define('DIR_VENDOR', '../../vendor/');
if (file_exists(DIR_VENDOR . 'autoload.php')) {
    require_once(DIR_VENDOR . 'autoload.php');
}

$dotenv = Dotenv\Dotenv::createImmutable('../../');
$dotenv->load();

$db = new DB();

// получаем данные
$data = json_decode(file_get_contents("php://input"));

// TODO
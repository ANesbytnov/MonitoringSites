<?php
error_reporting(E_ALL);
ini_set('display_errors', true);
ini_set('display_startup_errors', true);

// требуемые заголовки
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

// создание пользователя
if (
    !empty($data->firstname) &&
    !empty($data->lastname) &&
    !empty($data->email) &&
    !empty($data->password) &&
    $db->addUser($data->firstname, $data->lastname, $data->email, $data->password)
) {

    // устанавливаем код ответа
    http_response_code(200);

    // покажем сообщение о том, что пользователь был создан
    echo json_encode(array("message" => "Пользователь был создан."));

} else {
    // сообщение, если не удаётся создать пользователя

    // устанавливаем код ответа
    http_response_code(400);

    // покажем сообщение о том, что создать пользователя не удалось
    echo json_encode(array("message" => "Невозможно создать пользователя."));
}
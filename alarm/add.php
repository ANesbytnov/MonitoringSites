<?php
// необходимые HTTP-заголовки
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

// получаем отправленные данные
$data = json_decode(file_get_contents("php://input"));

if (
    !empty($data->url) &&
    !empty($data->result)
) {

    include_once '../config/DB.php';

    // Autoloader
    define('DIR_VENDOR', '../vendor/');
    if (file_exists(DIR_VENDOR . 'autoload.php')) {
        require_once(DIR_VENDOR . 'autoload.php');
    }

    $dotenv = Dotenv\Dotenv::createImmutable('../');
    $dotenv->load();

    $db = new DB();

    $result = $db->addAlarm($data->url, $data->result);

    if ($result !== false) {
        http_response_code(200);
        echo json_encode(array("message" => "Alarm added successfully"), JSON_UNESCAPED_UNICODE);
    } else {
        http_response_code(503);
        echo json_encode(array("message" => "Error"), JSON_UNESCAPED_UNICODE);
    }
}
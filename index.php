<?php
    error_reporting(E_ALL);
    ini_set('display_errors', true);
    ini_set('display_startup_errors', true);

    /*
    TODO: Надо разделить файл, который вызывается по крону и который выполняет проверку сайтов
    и интерфейс для пользователя
    */

    require_once 'Monitoring.php';
    require_once '/objects/Alarm.php';
    require_once '/config/DB.php';

    // Autoloader
    define('DIR_VENDOR', __DIR__.'/vendor/');
    if (file_exists(DIR_VENDOR . 'autoload.php')) {
        require_once(DIR_VENDOR . 'autoload.php');
    }

    $dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
    $dotenv->load();

    // Получаем список сайтов для проверки
    $db = new DB();
    $sites = $db->getSites();
    unset($db);

    // Выполняем проверку
    $monitor = new Monitoring($sites);
    $monitoring_result = $monitor->checkSites();

    // Выполняем отправку алярмов
    $alarm = new Alarm($monitoring_result);
    $alarm->checkResults();

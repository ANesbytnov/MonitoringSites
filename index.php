<?php
    /*
    TODO: Надо разделить файл, который вызывается по крону и который выполняет проверку сайтов
    и интерфейс для пользователя
    */

    require_once 'Monitoring.php';
    require_once 'Alarm.php';
    require_once 'DB.php';

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

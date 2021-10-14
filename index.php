<?php
    /*
    TODO: Надо разделить файл, который вызывается по крону и который выполняет проверку сайтов
    и интерфейс для пользователя
    */

    require_once 'Monitoring.php';
    require_once 'Alarm.php';
    require_once 'DB.php';

    // TODO: Подумать как задавать настройки (класс? отдельный cfg-файл?)
    const data_file = 'data.txt';
    $sites = file(data_file, FILE_IGNORE_NEW_LINES);

    $monitor = new Monitoring($sites);
    $monitoring_result = $monitor->checkSites();

    $db = new DB();
    $alarm = new Alarm($monitoring_result, $db);
    $alarm->checkResults();

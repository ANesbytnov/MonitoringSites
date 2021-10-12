<?php
    require_once 'Monitoring.php';
    require_once 'Alarm.php';

    // TODO: Подумать как задавать настройки (класс? отдельный cfg-файл?)
    const data_file = 'data.txt';

    $sites = file_get_contents(data_file);

    $monitor = new Monitoring($sites);

    $monitoring_result = $monitor->checkSites();

    $alarm = new Alarm($monitoring_result);

    $alarm->checkResults();
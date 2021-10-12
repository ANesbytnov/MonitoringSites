<?php
    require_once 'Monitoring.php';

    $sites = file_get_contents('data.txt');

    $monitor = new Monitoring($sites);

    $result = $monitor->checkSites();


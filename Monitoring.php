<?php

class Monitoring {

    private $sites;

    function __construct($sites) {
        // TODO: проверить $sites - массив строк и каждая строка = урл
        $this->sites = $sites;
    }

    function checkUrl($url) {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        // don't download content
        curl_setopt($ch, CURLOPT_NOBODY, 1);
        curl_setopt($ch, CURLOPT_FAILONERROR, true);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

        $result = true; // может измениться на строку в случае ошибки

        if (curl_exec($ch) === false) {
            if (curl_errno($ch)) {
                $result = curl_errno($ch) . ' ' . curl_error($ch);
            } else {
                $result = $ch . ' Неизвестная ошибка';
            }
        }

        curl_close($ch);

        return $result;
    }

    function getSitemapXmlContent($sitemapXml) {
        // TODO: Метод должен вернуть содержимое xml-карты по присланному полному урлу
    }


    function getSitemapXmlUrl($robots) {
        // TODO: Метод должен вернуть полный урл до ресурса sitemap.xml сайта
    }

    function getRobots($site) {
        // TODO: Метод должен по урлу главной страницы запросить файл robots.txt и вернуть его содержимое
    }


    function getCheckingUrlsFromSite($site) {
        // TODO: Нужен интеллектуальный алгоритм выбора нескольких страниц сайта для проверки
        /*
        Первый вариант - по $site взять robots.txt,
        в нём найти ссылку на xml-карту сайта,
        и уже из карты сайта взять рандомно несколько страниц для проверки
        */

        // Пока что проверяем только одну главную страницу
        return [$site];
    }

    function checkSite($site) {
        $result = [];

        foreach ($this->getCheckingUrlsFromSite($site) as $url) {
            $result[$url] = $this->checkUrl($url);
        }

        return $result;
    }

    function checkSites() {
        $result = [];

        foreach ($this->sites as $site) {
            $result[$site] = $this->checkSite($site);
        }

        return $result;
    }
}
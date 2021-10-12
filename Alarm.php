<?php

const telegrambotCfgFile = 'itgidsbotping.cfg.php';

if (file_exists(telegrambotCfgFile)) {
    // Так как репозиторий публичный и нельзя светить настройки телеграм бота
    include_once telegrambotCfgFile;
} else {
    define("BOT_TOKEN", '');
    define("API_URL", '');
    define("chatID", '');
}

class Alarm {
    private $monitoring_result;

    function __construct($monitoring_result) {
        // TODO: проверить $sites - массив строк и каждая строка = урл
        $this->monitoring_result = $monitoring_result;
    }

    function sendAlarmTelegram($url, $result) {
        $reply = urlencode('Сайт ' . $url . ' недоступен. Ошибка: ' . $result);
        $sendto = API_URL . 'sendmessage?chat_id=' . chatID . '&text=' . $reply;
        file_get_contents($sendto);
    }


    function checkResults() {
        foreach ($this->monitoring_result as $site_result) {
            foreach ($site_result as $url => $result) {
                if ($result !== true) {
                    $this->sendAlarmTelegram($url, $result);
                }
            }
        }
    }

}
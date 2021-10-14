<?php
// TODO: Подумать, как лучше реализовать отправку данных в БД из этого класса. Сейчас в конструктор прилетает db-переменная
require_once 'DB.php';

const telegrambotCfgFile = 'itgidsbotping.cfg.php';

if (file_exists(telegrambotCfgFile)) {
    // Так как репозиторий публичный и нельзя светить настройки телеграм бота
    include_once telegrambotCfgFile;
} else {
    define("BOT_TOKEN", '');
    define("API_URL", '');
    define("chatID", '');
}

// TODO: Подумать, как уведомлять нескольких человек в телеграме, в email? Возможно в БД надо сделать структуру вида САЙТ => КОГО_И_КАК_УВЕДОМИТЬ


class Alarm {
    private $monitoring_result;
    private $db;

    function __construct($monitoring_result) {
        // TODO: проверить $monitoring_result
        // TODO: проверить $db
        $this->monitoring_result = $monitoring_result;
        $this->db = new DB();
    }

    // Отправка уведомления в телеграм
    private function sendAlarmTelegram($url, $result) {
        $reply = urlencode('Сайт ' . $url . ' недоступен. Ошибка: ' . $result);
        $sendto = API_URL . 'sendmessage?chat_id=' . chatID . '&text=' . $reply;
        file_get_contents($sendto);
    }

    // Отправка уведомления на почтовый ящик
    private function sendEmail($url, $result) {
        // TODO: Написать функцию отправки email НА КАКОЙ email? КАК ЕГО ЗАДАВАТЬ?
    }

    // Отправка уведомления в VK
    private function sendVK($url, $result) {
        // TODO: Подумать насколько это актуально, реально ли это сделать
    }

    // Отправка уведомления в Whatsapp
    private function sendWhatsapp($url, $result) {
        // TODO: Подумать насколько это актуально, реально ли это сделать
    }

    // Отправка уведомления в Viber
    private function sendViber($url, $result) {
        // TODO: Подумать насколько это актуально, реально ли это сделать
    }


    // Функция выполняет необходимые действия в случае обнаружения "ошибки" при проверке урла
    private function alarm($url, $result) {
        $this->sendAlarmTelegram($url, $result);
        // $this->sendEmail($url, $result);
        // $this->sendVK($url, $result);
        // $this->sendWhatsapp($url, $result);
        // $this->sendViber($url, $result);
        if ($this->db) {
            $this->db->addAlarm($url, $result);
        }
    }

    // Проверка всех результатов однократной проверки
    function checkResults() {
        foreach ($this->monitoring_result as $site_result) {
            foreach ($site_result as $url => $result) {
                if ($result !== true) {
                    /*
                     *  TODO: Пока что о каждом урле пользователь будет получать отдельное уведомление
                     *  Это неудобно, если, например, 10 страниц с ошибкой, то придёт 10 писем на почту
                     *  Нужно создавать некий контейнер ошибочных страниц по каждому пользователю и отправлять единое уведомление
                     */
                    $this->alarm($url, $result);
                }
            }
        }
    }

}
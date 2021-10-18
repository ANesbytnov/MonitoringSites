<?php

// TODO: Подумать, как лучше реализовать отправку данных в БД из этого класса. Сейчас в конструктор прилетает db-переменная
require_once '../config/DB.php';

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
        $reply = urlencode('Страница ' . $url . ' недоступна. Ошибка: ' . $result);
        $sendto = $_ENV['API_URL'] . 'sendmessage?chat_id=' . $_ENV['CHAT_ID'] . '&text=' . $reply;
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
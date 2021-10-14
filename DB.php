<?php

/*
TODO: Подумать над структурой БД

Структура БД

sites {
    site_id: autoincrement,
    sitename : string,
    lastedited: datetime // время последнего редактирования (или создания) строки
}

alarm {
    ??? sitename: string, TODO: может написать функцию, которая из url динамически будет sitename получать и тогда не надо хранить её в БД
    url: string,
    result: string,
    datetime: datetime
}

users {
    user_id: autoincrement,
    login: string,
    pass: string,
    email: string,
    telegram: string,
    vk: string,
    whatsapp: string,
    viber: string,
    lastedited: datetime // время последнего редактирования (или создания) строки
}

users_actions {
    ua_id: autoincrement,
    user_id: integer,
    action: string, // например, "Добавил страницу такую-то в мониторинг", "Обновил поле telegram в своем профиле", "Вошел в систему", "Вышел из системы" и т.д.
    action_datetime: datetime // время добавления строки в БД
}

user_site_alarm_actions {
    usaa_id: autoincrement,
    user_id: integer,
    site_id: integer,
    email: boolean, // если true, значит уведомляем юзера по email об "ошибке" при проверке какой-либо страницы сайта
    telegram: boolean,
    vk: boolean,
    whatsapp: boolean,
    viber: boolean,
    lastedited: datetime // время последнего редактирования (или создания) строки
    // TODO: возможно будут ещё поля вида ЧЕРЕЗ КАКОЙ ИНТЕРВАЛ ВРЕМЕНИ УВЕДОМЛЯТЬ О ПРОДОЛЖАЮЩЕЙСЯ ОШИБКЕ
}


*/
class DB
{
    // TODO: Где лучше хранить настройки БД?
    const dbServer = '';
    const dbName = '';
    const dbUser = '';
    const dbPass = '';

    private $dbconn;

    public function __construct() {
        $this->connect();
    }

    public function __destruct() {
        $this->disconnect();
    }

    public function connect() {
        // TODO: Написать подключение к БД и записать во $dbconn
        // TODO: Дописать проверку, возможно мы уже подключены
    }

    public function disconnect() {
        // TODO: Написать код отключения от БД и очищения $dbconn
        // TODO: Дописать проверку, возможно мы уже отключены
    }

    public function getQuery($sql) {
        // TODO: Написать код, выполняющий SQL-запрос (может быть SELECT, DELETE, UPDATE) из входного параметра, вернуть результат выполнения запроса
    }

    public function addAlarm($url, $result) {
        // TODO: Написать код, добавляющий в лог алярмов в БД случившийся алярм
    }

    public function getLastAlarm($url = '') {
        // TODO: Функция должна вернуть ассоциативный массив из одного элемента вида ['url' = > $url, 'result' => $result, 'datetime' => $datetime события]
        // TODO: Если задан входной параметр $url, то ищется самый поздний по времени алярм именно по этому урлу, ИНАЧЕ ищется просто самый последний алярм
    }

    public function getSiteAlarmActions($url) {

    }

}
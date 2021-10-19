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
    alarm_id: autoincrement,
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

    private $dbconn = null;
//    private $dbconn_error = null;

    public function __construct() {
        $this->connect();
    }

    public function connected() {
        return $this->dbconn != null;
    }

    public function __destruct() {
        $this->disconnect();
    }

    // Подключение к БД
    public function connect() {
        if ($this->connected()) {
            // Мы уже куда-то подключены, возвращаем false
            return false;
        }

        // Create connection
        $conn = new mysqli($_ENV['DBSERVER'], $_ENV['DBUSER'], $_ENV['DBPASS'], $_ENV['DBNAME']);

        // Check connection
        if ($conn->connect_error) {
//            $this->dbconn_error = $conn->connect_error;
            $this->dbconn = null;
            return false;
        }

//        $this->dbconn_error = null;
        $this->dbconn = $conn;

        return true;
    }

    // Отключение от БД
    public function disconnect() {
        if (!$this->connected()) {
            // Мы никуда не подключены
            return false;
        }
        $this->dbconn->close();
        $this->dbconn = null;
        $this->dbconn_error = null;
        return true;
    }

    // Выполнение SQL-запроса
    // Нужно запрещать выполнять SQL-запросы напрямую (поэтому private). Используем функции обертки (например, addAlarm, getLastAlarm).
    private function doQuery($sql) {
        // TODO: Написать код, выполняющий SQL-запрос (может быть SELECT, DELETE, UPDATE) из входного параметра, вернуть результат выполнения запроса
        if (!$this->connected()) {
            return false;
        }

        return $this->dbconn->query($sql);
    }

    // Добавление строки в таблицу БД alarm
    public function addAlarm($url, $result) {
        $query = "
            INSERT INTO alarm
            SET
                url = ?,
                result = ?,
                datetime = NOW()
        ";

        // подготовка запроса
        $stmt = mysqli_prepare($this->dbconn, $query);

        // инъекция
        $safe_url = htmlspecialchars(strip_tags($url));
        $safe_result = htmlspecialchars(strip_tags($result));

        // привязываем значения
        mysqli_stmt_bind_param($stmt, "ss", $safe_url, $safe_result, );


        // Выполняем запрос
        // Если выполнение успешно, то информация о пользователе будет сохранена в базе данных
        return $stmt->execute();
    }

    // Добавление пользователя
    public function addUser($firstname, $lastname, $email, $password) {
        $query = "
            INSERT INTO `users` (`firstname`, `lastname`, `email`, `password`)
            VALUES (?, ?, ?, ?)
        ";

        // подготовка запроса
        $stmt = mysqli_prepare($this->dbconn, $query);

        // инъекция
        $safe_firstname = htmlspecialchars(strip_tags($firstname));
        $safe_lastname = htmlspecialchars(strip_tags($lastname));
        $safe_email = htmlspecialchars(strip_tags($email));
        $safe_password = htmlspecialchars(strip_tags($password));

        // для защиты пароля
        // хешируем пароль перед сохранением в базу данных
        $password_hash = password_hash($safe_password, PASSWORD_BCRYPT);

        mysqli_stmt_bind_param($stmt, "ssss", $safe_firstname, $safe_lastname, $safe_email, $password_hash);

        // Выполняем запрос
        // Если выполнение успешно, то информация о пользователе будет сохранена в базе данных
        return $stmt->execute();
    }

    public function getLastAlarm($url = '') {
        // TODO: Функция должна вернуть ассоциативный массив из одного элемента вида ['url' = > $url, 'result' => $result, 'datetime' => $datetime события]
        // TODO: Если задан входной параметр $url, то ищется самый поздний по времени алярм именно по этому урлу, ИНАЧЕ ищется просто самый последний алярм
        // TODO: Подумать вообще нужен ли такой метод?
    }

    // Получение настроек предупреждений для заданного пользователя
    public function getSiteAlarmActions($url, $user_id) {
        // TODO: Написать метод
    }

    // Получение списка проверяемых сайтов
    public function getSites() {
        $result = $this->doQuery('
            SELECT sitename
            FROM   `sites`
        ');

        if (!$result) {
            return false;
        }

        $rows = [];
        while ($row = $result->fetch_assoc()) {
            $rows[] = $row['sitename'];
        }

        return $rows;
    }

}
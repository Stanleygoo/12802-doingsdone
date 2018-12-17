<?php

require_once('mysql_helper.php');

/**
 * Выполняет подключение к базе данных
 * @return (mysqli|bool) ресурс подключения в случае успешного подключения к базе данных или false в случае неудачного подключения
 */
function db_connect() {
    static $connection;

    if (isset($connection)) {
        return $connection;
    };

    $db = require(ROOT_PATH . '/config/db.php');
    $connection = mysqli_init();
    mysqli_options($connection, MYSQLI_OPT_INT_AND_FLOAT_NATIVE, 1);
    $conn_result = mysqli_real_connect(
        $connection,
        $db['host'],
        $db['user'],
        $db['password'],
        $db['database']
    );
    mysqli_set_charset($connection, 'utf8');
    return $conn_result ? $connection : false;
}


/**
 * Возвращает последнюю ошибку, связанную с базой данных
 * @return string строка с описанием последней ошибки
 */
function db_error() {
    $connection = db_connect();
    return mysqli_connect_errno()
        ? mysqli_connect_error()
        : mysqli_error($connection);
}

/**
 * Функция для получения данных из БД
 * @param string $sql SQL-запрос с плейсхолдерами вместо значений
 * @param array $data данные для вставки на место плейсхолдеров
 *
 * @return (mixed|bool) результат выборки или false в случае неудачной операции
 */
function db_fetch_data($sql, $data = []) {
    $connection = db_connect();

    if (!$connection) {
        return false;
    };

    $stmt = db_get_prepare_stmt($connection, $sql, $data);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    return $result
        ? mysqli_fetch_all($result, MYSQLI_ASSOC)
        : false;
}


/**
 * Функция для вставки данных в БД
 * @param string $sql SQL-запрос с плейсхолдерами вместо значений
 * @param array $data данные для вставки на место плейсхолдеров
 *
 * @return (int|bool) id добавленной записи или false в случае неудачной операции
 */
function db_insert_data($sql, $data = []) {
    $connection = db_connect();

    if (!$connection) {
        return false;
    };

    $stmt = db_get_prepare_stmt($connection, $sql, $data);
    $result = mysqli_stmt_execute($stmt);

    return $result
        ? mysqli_insert_id($connection)
        : false;
}


/**
 * Функция для обновления данных в БД
 * @param string $sql SQL-запрос с плейсхолдерами вместо значений
 * @param array $data данные для вставки на место плейсхолдеров
 *
 * @return (bool) успешность операции
 */
function db_update_data($sql, $data = []) {
    $connection = db_connect();

    if (!$connection) {
        return false;
    };

    $stmt = db_get_prepare_stmt($connection, $sql, $data);
    $result = mysqli_stmt_execute($stmt);

    return $result;
}

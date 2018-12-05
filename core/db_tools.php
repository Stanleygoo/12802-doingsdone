<?php

require_once('mysql_helper.php');

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


function db_error() {
    $connection = db_connect();
    return mysqli_connect_errno()
        ? mysqli_connect_error()
        : mysqli_error($connection);
}


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

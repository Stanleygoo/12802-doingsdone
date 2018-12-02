<?php

function db_connect() {
    $db = require 'config/db.php';

    $conn = mysqli_init();

    mysqli_options($conn, MYSQLI_OPT_INT_AND_FLOAT_NATIVE, 1);

    $conn_result = mysqli_real_connect(
        $conn,
        $db['host'],
        $db['user'],
        $db['password'],
        $db['database']
    );

    mysqli_set_charset($conn, 'utf8');

    return !$conn_result ? false : $conn;
}

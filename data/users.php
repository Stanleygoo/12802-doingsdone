<?php

require_once(ROOT_PATH . '/core/db_tools.php');

function getUserByEmail($user_email) {
    $user_sql = "
        SELECT `id`, `name`, `email`, `password`
        FROM `users`
        WHERE `email` = ?
    ";

    return db_fetch_data($user_sql, [$user_email]);
}

function addUser($user) {
    $add_user_sql = "
        INSERT INTO `users` (`name`, `email`, `password`)
        VALUES (?, ?, ?)
    ";

    return db_insert_data($add_user_sql, [
        $user['name'],
        $user['email'],
        $user['password']
    ]);
}

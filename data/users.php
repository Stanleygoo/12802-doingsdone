<?php

require_once(ROOT_PATH . '/core/db_tools.php');

/**
 * Функция получения пользователя из БД по его email'у
 * @param string $user_email email пользователя
 *
 * @return (mixed|bool) результат выборки или false в случае неудачной операции
 */
function get_user_by_email($user_email) {
    $user_sql = "
        SELECT `id`, `name`, `email`, `password`
        FROM `users`
        WHERE `email` = ?
    ";

    return db_fetch_data($user_sql, [$user_email]);
}


/**
 * Функция для добавления пользователя в БД
 * @param array $user данные пользователя
 *
 * @return (int|bool) id добавленной записи или false в случае неудачной операции
 */
function add_user($user) {
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

<?php

require_once(ROOT_PATH . '/core/db_tools.php');

/**
 * Функция для получения проектов из БД
 * @param int $user_id id пользователя, для которого выбираются проекты
 *
 * @return (mixed|bool) результат выборки или false в случае неудачной операции
 */
function get_all_projects($user_id) {
    $projects_list_sql = "
        SELECT p.id, p.name, COUNT(t.project_id) as tasks_count
        FROM projects p
        LEFT JOIN tasks t ON p.id = t.project_id
        WHERE p.author_id = ?
        GROUP BY p.id
    ";

    return db_fetch_data($projects_list_sql, [$user_id]);
}


/**
 * Функция для получения одного проекта из БД
 * @param int $user_id id пользователя, для которого выбирается проект
 * @param int $project_id id проекта
 *
 * @return (mixed|bool) результат выборки или false в случае неудачной операции
 */
function get_project_by_id($user_id, $project_id) {
    $projects_list_sql = "
        SELECT p.id, p.name
        FROM projects p
        WHERE p.author_id = ? AND p.id = ?
    ";

    return db_fetch_data($projects_list_sql, [$user_id, $project_id]);
}


/**
 * Функция для добавления проекта в БД
 * @param string $name название проекта
 * @param int $user_id id пользователя, для которого создается проект
 *
 * @return (int|bool) id добавленной записи или false в случае неудачной операции
 */
function add_project($name, $user_id) {
    $add_project_sql = "
        INSERT INTO `projects` (`name`, `author_id`)
        VALUES (?, ?)
    ";

    return db_insert_data($add_project_sql, [$name, $user_id]);
}

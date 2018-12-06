<?php

require_once(ROOT_PATH . '/core/db_tools.php');

function getAllTasks($user_id) {
    $tasks_list_sql = "
        SELECT *
        FROM `tasks`
        WHERE `author_id` = ?
    ";

    return db_fetch_data($tasks_list_sql, [$user_id]);
}

function getTasksByProject($user_id, $project_id) {
    $tasks_list_sql = "
        SELECT *
        FROM `tasks`
        WHERE `author_id` = ?
        AND `project_id` = ?
    ";

    return db_fetch_data($tasks_list_sql, [$user_id, $project_id]);
}

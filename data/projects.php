<?php

require_once(ROOT_PATH . '/core/db_tools.php');

function getAllProjects($user_id) {
    $projects_list_sql = "
        SELECT p.id, p.name, COUNT(t.project_id) as tasks_count
        FROM projects p
        LEFT JOIN tasks t ON p.id = t.project_id
        WHERE p.author_id = ?
        GROUP BY p.id
    ";

    return db_fetch_data($projects_list_sql, [$user_id]);
}

function getProjectById($user_id, $project_id) {
    $projects_list_sql = "
        SELECT p.id, p.name
        FROM projects p
        WHERE p.author_id = ? AND p.id = ?
    ";

    return db_fetch_data($projects_list_sql, [$user_id, $project_id]);
}

function addProject($name, $user_id) {
    $add_project_sql = "
        INSERT INTO `projects` (`name`, `author_id`)
        VALUES (?, ?)
    ";

    return db_insert_data($add_project_sql, [$name, $user_id]);
}

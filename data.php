<?php

// получение проектов для данного юзера
function getProjectsOfUser($conn, $user_id) {
    $projects_list_sql = "
        SELECT `id`, `name`
        FROM `projects`
        WHERE `author_id` = $user_id
    ";

    $projects_query_result = mysqli_query($conn, $projects_list_sql);

    return $projects_query_result
        ? mysqli_fetch_all($projects_query_result, MYSQLI_ASSOC)
        : false;
}

// получение задач для данного юзера
function getTasksOfUser($conn, $user_id) {
    $tasks_list_sql = "
        SELECT *
        FROM `tasks`
        WHERE `author_id` = $user_id
    ";

    $tasks_query_result = mysqli_query($conn, $tasks_list_sql);

    return $tasks_query_result
        ? mysqli_fetch_all($tasks_query_result, MYSQLI_ASSOC)
        : false;
}

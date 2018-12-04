<?php

// получение проектов для данного юзера
function getProjectsOfUser($conn, $user_id) {
    $user_id = mysqli_real_escape_string($conn, $user_id);
    $projects_list_sql = "
        SELECT projects.id, projects.name, COUNT(project_id) as tasks_count
        FROM projects
        JOIN tasks ON projects.id = tasks.project_id
        WHERE projects.author_id = '$user_id'
        GROUP BY tasks.project_id
    ";

    $projects_query_result = mysqli_query($conn, $projects_list_sql);

    return $projects_query_result
        ? mysqli_fetch_all($projects_query_result, MYSQLI_ASSOC)
        : false;
}

// получение задач для данного юзера
function getTasksOfUser($conn, $user_id, $project_id) {
    $project_id = mysqli_real_escape_string($conn, $project_id);
    $tasks_list_sql = "
        SELECT *
        FROM `tasks`
        WHERE `author_id` = $user_id
    " . ($project_id !== null
            ? "AND `project_id` = '$project_id'"
            : ""
        );

    $tasks_query_result = mysqli_query($conn, $tasks_list_sql);

    return $tasks_query_result
        ? mysqli_fetch_all($tasks_query_result, MYSQLI_ASSOC)
        : false;
}

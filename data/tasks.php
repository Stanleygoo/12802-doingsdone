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

function getTasks($user_id, $project_id, $filterName = null) {
    $filters = [
        'today' => '
            AND `deadline` >= CURDATE()
            AND `deadline` < CURDATE() + INTERVAL 1 DAY
        ',
        'tomorrow' => '
            AND `deadline` >= CURDATE() + INTERVAL 1 DAY
            AND `deadline` < CURDATE() + INTERVAL 2 DAY
        ',
        'expired' => '
            AND `deadline` < CURDATE()
            AND `status` = "0"
        '
    ];

    $filter_sql = $filters[$filterName] ?? '';

    $base_sql = "
        SELECT *
        FROM `tasks`
        WHERE `author_id` = ?
        AND `project_id` = ?
    ";

    $result_sql = $base_sql . $filter_sql;

    return db_fetch_data($result_sql, [$user_id, $project_id]);
}

function toggleTask($task_id, $value) {
    $toggle_sql = "
        UPDATE `tasks`
        SET `status` = ?
        WHERE `id` = ?
    ";

    return db_update_data($toggle_sql, [$value, $task_id]);
}

function addTask($task) {
    $names_map = [
        'name' => [
            'column' => 'name',
            'sql' => '?'
        ],
        'date' => [
            'column' => 'deadline',
            'sql' => 'STR_TO_DATE(?, "%Y-%m-%d")'
        ],
        'file' => [
            'column' => 'file',
            'sql' => '?'
        ],
        'project' => [
            'column' => 'project_id',
            'sql' => '?'
        ],
        'author_id' => [
            'column' => 'author_id',
            'sql' => '?'
        ]
    ];

    $task = array_filter($task);

    $fields = array_keys($task);

    $columns = implode(',', array_map(function($field_name) use($names_map) {
        return $names_map[$field_name]['column'];
    }, $fields));

    $sql_values = implode(',', array_map(function($field_name) use($names_map) {
        return $names_map[$field_name]['sql'];
    }, $fields));

    $task_data = array_map(function($field_name) use($task) {
        return $task[$field_name];
    }, $fields);

    $add_task_sql = "
        INSERT INTO `tasks` ($columns)
        VALUES ($sql_values)
    ";

    return db_insert_data($add_task_sql, $task_data);
}

<?php

require_once(ROOT_PATH . '/core/db_tools.php');

function getTasks(
    $user_id,
    $project_id = null,
    $filterName = null,
    $search_query = null
) {
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

    $sql = "
        SELECT *
        FROM `tasks`
        WHERE `author_id` = ?
    ";

    $sql .= ($project_id ? ' AND `project_id` = ?' : '');
    $sql .= ($search_query ? ' AND MATCH(`name`) AGAINST(? IN BOOLEAN MODE)' : '');
    $sql .= ($filters[$filterName] ?? '');

    return db_fetch_data($sql, array_merge(
        [$user_id],
        $project_id ? [$project_id] : [],
        $search_query ? [$search_query] : []
    ));
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

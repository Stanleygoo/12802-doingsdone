<?php

require_once(ROOT_PATH . '/core/db_tools.php');

function add_filter_condition($filter_name) {
    $filters = [
        'today' => '
            `deadline` >= CURDATE() AND `deadline` < CURDATE() + INTERVAL 1 DAY
        ',
        'tomorrow' => '
            `deadline` >= CURDATE() + INTERVAL 1 DAY AND `deadline` < CURDATE() + INTERVAL 2 DAY
        ',
        'expired' => '
            `deadline` < CURDATE() AND `status` = "0"
        '
    ];

    return $filters[$filter_name] ?? '';
}

function add_project_condition() {
    return '`project_id` = ?';
}

function add_search_condition() {
    return 'MATCH(`name`) AGAINST(? IN BOOLEAN MODE)';
}

function get_tasks($user_id, $params = []) {
    $sql = "
        SELECT *
        FROM `tasks`
        WHERE `author_id` = ?
    ";

    $where = [];

    if (($params['project_id'] ?? null)) {
        $where[] = add_project_condition();
    }

    if (($params['search_query'] ?? null)) {
        $where[] = add_search_condition();
    }

    if (($params['filter_name'] ?? null)) {
        $where[] = add_filter_condition($params['filter_name']);
    }

    $sql .= count($where) > 0
        ? ' AND ' . implode(' AND ', $where)
        : '';

    $bind_data = array_merge(
        [$user_id],
        (($params['project_id'] ?? null)) ? [$params['project_id']] : [],
        (($params['search_query'] ?? null)) ? [$params['search_query']] : []
    );

    return db_fetch_data($sql, $bind_data);
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

function getExpiredTasks() {
    $sql = "
        SELECT
            tasks.name as task_name,
            tasks.deadline,
            users.id as user_id,
            users.name as user_name,
            users.email as user_email
        FROM tasks
        JOIN users ON users.id = tasks.author_id
        WHERE deadline >= NOW()
        AND deadline < DATE_ADD(NOW(), INTERVAL 1 HOUR)
    ";

    return db_fetch_data($sql);
}

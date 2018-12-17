<?php

require_once(ROOT_PATH . '/core/db_tools.php');

/**
 * Добавляет sql-запрос для фильтрации задач по датам и завершенности
 * @param string $filter_name имя фильтра
 *
 * @return string sql с фильтрацией
 */
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


/**
 * Добавляет sql-запрос для фильтрации задач по id проекта
 *
 * @return string sql с фильтрацией
 */
function add_project_condition() {
    return '`project_id` = ?';
}


/**
 * Добавляет sql-запрос для полнотекстового поиска
 *
 * @return string sql с фильтрацией
 */
function add_search_condition() {
    return 'MATCH(`name`) AGAINST(? IN BOOLEAN MODE)';
}


/**
 * Функция для получения данных о задачах
 * @param int $user_id id пользователя
 * @param array $params дополнительные параметры фильтрации
 * @return (mixed|bool) результат выборки или false в случае неудачной операции
 */
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


/**
 * Функция для смена состояния выполненности задачи
 * @param int $task_id id задачи
 * @param int $status статус выполненности задачи (0 | 1)
 *
 * @return bool успешность операции
 */
function toggle_task($task_id, $status) {
    $toggle_sql = "
        UPDATE `tasks`
        SET `status` = ?
        WHERE `id` = ?
    ";

    return db_update_data($toggle_sql, [$status, $task_id]);
}


/**
 * Функция для добавления задачи в БД
 * @param array $task данные задачи
 *
 * @return (int|bool) id добавленной записи или false в случае неудачной операции
 */
function add_task($task) {
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

/**
 * Функция для получения задач, срок которых истекает в течение текущего часа
 *
 * @return (mixed|bool) результат выборки или false в случае неудачной операции
 */
function get_expired_tasks() {
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

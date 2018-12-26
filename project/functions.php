<?php

/**
 * Заносит дополнительные данные в массив проектов для создания навигации по ним
 * @param array $projects массив проектов
 * @param int $active_project_id id текущего выбранного проекта
 * @param array $query_params массив параметров запроса
 * @param string $pathname имя скрипта, который бкдет использоваться как базовый путь
 *
 * @return array подготовленный массив проектов
 */
function fill_projects_data($projects, $active_project_id, $query_params, $pathname) {
    return array_map(function($project) use($active_project_id, $query_params, $pathname) {
        $query_params['project_id'] = $project['id'];
        $project['url'] = '/' . $pathname . '?' . http_build_query($query_params);
        $project['is_active'] = $project['id'] === (int)$active_project_id;
        return $project;
    }, $projects);
}


/**
 * Функция для фильтрации задач на завершенность
 * @param array $tasks массив задач
 * @param int $show_complete_tasks флаг для задания условия фильтровать или нет (0 | 1)
 *
 * @return array массив отфильтрованных задач
 */
function filter_completed_tasks($tasks, $show_complete_tasks) {
    return array_filter($tasks, function($task) use($show_complete_tasks) {
        return !($show_complete_tasks === 0 && $task['is_done']);
    });
}


/**
 * Функция для определения, что до завершения задачи осталось меньше 24 часов
 * @param array $task задача, для котороый проверяется уловие
 *
 * @return bool
 */
function is_important_task($task) {
    $deadline = strtotime($task['deadline']);
    $hours_in_day = 24;
    $seconds_in_hour = 3600;
    $current_time = time();
    $diff = $deadline - $current_time;
    return $deadline
        ? $diff >= 0 && floor($diff / $seconds_in_hour) <= $hours_in_day
        : false;
}


/**
 * Функция для добавления информации о важности задачи
 * @param array $tasks массив задач
 *
 * @return array преобразованный массив задач
 */
function fill_important_task($tasks) {
    return array_map(function($task) {
        $task['is_important'] = is_important_task($task);
        return $task;
    }, $tasks);
}


/**
 * функция для добавления информации о завершенности задачи
 * @param array $tasks массив задач
 *
 * @return array преобразованный массив задач
 */
function fill_done_task($tasks) {
    return array_map(function($task) {
        $task['is_done'] = $task['status'] === '1';
        return $task;
    }, $tasks);
}


/**
 * Функция для проверки на аутентификацию пользователя.
 * Если пользователь не залогинен, то присходит перенаправление на страницу /guest.php
 * @return void
 */
function check_auth() {
    if (!isset($_SESSION['user'])) {
        header('Location: /guest.php');
        exit();
    }
}


/**
 * Функция для рендера основного лейаута страницы
 * @param array $data массив с данными и вспомогательными флагами
 * @return string отрендеренный html страницы
 */
function build_layout($data) {
    return view(VIEWS_PATH . 'shared/layout.php', [
        'content' => $data['content'] ?? '',
        'title' => $data['title'] ?? 'Дела в порядке',
        'show_bg' => $data['show_bg'] ?? false,
        'has_sidebar' => $data['has_sidebar'] ?? false,
        'user' => $data['user'] ?? null,
        'error_page' => $data['error_page'] ?? false
    ]);
}


/**
 * Фукнция для построения массива данных для отображения фильтра задач
 * @param string $active_filter значение текущего фильтра
 * @param int $active_project_id id текущего выбранного проекта
 * @param int $show_completed_tasks флаг для задания условия фильтровать или нет (0 | 1)
 * @param string $search_query значение поискового запроса
 *
 * @return array
 */
function build_task_filter(
    $active_filter,
    $active_project_id,
    $show_completed_tasks = 0,
    $search_query = null
) {
    $filters = [
        [
            'name' => 'Все задачи',
            'filterName' => null
        ],
        [
            'name' => 'Повестка дня',
            'filterName' => 'today'
        ],
        [
            'name' => 'Завтра',
            'filterName' => 'tomorrow'
        ],
        [
            'name' => 'Просроченные',
            'filterName' => 'expired'
        ]
    ];

    return array_map(function($filter) use($active_filter, $active_project_id, $show_completed_tasks, $search_query) {
        $filter['url'] = 'index.php?' . http_build_query([
            'project_id' => $active_project_id,
            'filter' => $filter['filterName'],
            'show_completed' => $show_completed_tasks,
            'query' => $search_query
        ]);
        $filter['is_active'] = $active_filter === $filter['filterName'];
        return $filter;
    }, $filters);
}

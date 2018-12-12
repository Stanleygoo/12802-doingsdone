<?php

function fill_projects_data($projects, $active_project_id, $query_params, $pathname) {
    return array_map(function($project) use($active_project_id, $query_params, $pathname) {
        $query_params['project_id'] = $project['id'];
        $project['url'] = '/' . $pathname . '?' . http_build_query($query_params);
        $project['is_active'] = $project['id'] === (int)$active_project_id;
        return $project;
    }, $projects);
}

function tasks_filter($tasks, $show_complete_tasks) {
    return array_filter($tasks, function($task) use($show_complete_tasks) {
        return !($show_complete_tasks === 0 && $task['is_done']);
    });
}

// функция для определения, что до завершения задачи осталось меньше 24 часов
function is_important_task($task) {
    $deadline = strtotime($task['deadline']);
    $hours_in_day = 24;
    $seconds_in_hour = 3600;
    $current_time = time();
    return $deadline
        ? floor(($deadline - $current_time) / $seconds_in_hour) <= $hours_in_day
        : false;
}

// функция для добавления информации о важности задачи
function fill_important_task($tasks) {
    return array_map(function($task) {
        $task['is_important'] = is_important_task($task);
        return $task;
    }, $tasks);
}

// функция для добавления информации о завершенности задачи
function fill_done_task($tasks) {
    return array_map(function($task) {
        $task['is_done'] = $task['status'] === '1';
        return $task;
    }, $tasks);
}

function check_auth() {
    if (!isset($_SESSION['user'])) {
        header('Location: /guest.php');
        exit();
    }
}

function buildLayout($data) {
    return view(VIEWS_PATH . 'shared/layout.php', [
        'content' => $data['content'],
        'title' => $data['title'] ?? 'Дела в порядке',
        'show_bg' => $data['show_bg'] ?? false,
        'has_sidebar' => $data['has_sidebar'] ?? false,
        'user' => $data['user'],
        'error_page' => $data['error_page'] ?? false
    ]);
}

<?php

function include_template($name, $data) {
    if (!file_exists($name)) {
        return '';
    };
    ob_start();
    extract($data);
    require $name;
    return ob_get_clean();
}

function tasks_filter($tasks, $show_complete_tasks) {
    return array_filter($tasks, function($task) use($show_complete_tasks) {
        return !($show_complete_tasks === 0 && $task['is_done']);
    });
}

// функция для подсчета числа задач проекта
function get_tasks_count($tasks, $project_id) {
    $project_tasks = array_filter($tasks, function($task) use ($project_id) {
        return $task['project'] === $project_id;
    });

    return count($project_tasks);
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

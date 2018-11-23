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

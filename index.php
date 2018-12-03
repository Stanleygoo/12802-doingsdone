<?php

require_once 'functions.php';
require_once 'db_connect.php';
require_once 'data.php';

define('VIEWS_PATH', __DIR__ . '/templates/');

$current_user_id = 1;

$conn = db_connect();
if (!$conn) {
    $conn_error = mysqli_connect_error();
    echo "
        <pre><code>$conn_error</code></pre>
    ";
    return;
}

$projects = getProjectsOfUser($conn, $current_user_id);
if (!$projects) {
    $error = mysqli_error($conn);
    render_error($error);
};

$tasks = getTasksOfUser($conn, $current_user_id);
if (!$tasks) {
    $error = mysqli_error($conn);
    render_error($error);
}

// показывать или нет выполненные задачи
$show_complete_tasks = rand(0, 1);

// задачи, отфильтрованные и преобразованные для показа
$tasks = fill_done_task($tasks);
$tasks = fill_important_task($tasks);
$visible_tasks = tasks_filter($tasks, $show_complete_tasks);

$index_content = include_template(VIEWS_PATH . 'index.php', [
    'show_complete_tasks' => $show_complete_tasks,
    'visible_tasks' => $visible_tasks,
    'projects' => $projects
]);

$full_page = include_template(VIEWS_PATH . 'layout.php', [
    'title' => 'Дела в порядке',
    'user' => [
        'name' => 'Константин',
        'avatar'=> 'img/user-pic.jpg'
    ],
    'projects' => $projects,
    'tasks' => $tasks,
    'show_complete_tasks' => $show_complete_tasks,
    'content' => $index_content
]);

echo $full_page;

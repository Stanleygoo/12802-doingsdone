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

if (isset($_GET['project_id']) && empty($_GET['project_id'])) {
    http_response_code(404);
    echo "
        <b>404</b>. По Вашему запросу ничего не найдено
        <p>
            <a href='/'>Перейти на главную</a>
        </p>
    ";
    return;
}

$active_project_id = $_GET['project_id'] ?? null;

$projects = getProjectsOfUser($conn, $current_user_id);
if (!$projects) {
    $error = mysqli_error($conn);
    render_error($error);
};
$pathname = pathinfo(__FILE__, PATHINFO_BASENAME);
$projects = fill_projects_data(
    $projects,
    $active_project_id,
    $_GET,
    $pathname
);

$tasks = getTasksOfUser($conn, $current_user_id, $active_project_id);
if (!$tasks) {
    $error = mysqli_error($conn);
    render_error($error);
}

if (count($tasks) === 0) {
    http_response_code(404);
}

// показывать или нет выполненные задачи
$show_complete_tasks = rand(0, 1);

// задачи, отфильтрованные и преобразованные для показа
$tasks = fill_done_task($tasks);
$tasks = fill_important_task($tasks);
$visible_tasks = tasks_filter($tasks, $show_complete_tasks);

$index_content = include_template(VIEWS_PATH . 'index.php', [
    'show_complete_tasks' => $show_complete_tasks,
    'visible_tasks' => $visible_tasks
]);

$full_page = include_template(VIEWS_PATH . 'layout.php', [
    'title' => 'Дела в порядке',
    'user' => [
        'name' => 'Константин',
        'avatar'=> 'img/user-pic.jpg'
    ],
    'projects' => $projects,
    'show_complete_tasks' => $show_complete_tasks,
    'content' => $index_content
]);

echo $full_page;

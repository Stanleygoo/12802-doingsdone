<?php

require_once('./bootstrap.php');

check_auth();

// если параметр project_id присутствует, но не задан
if (isset($_GET['project_id']) && empty($_GET['project_id'])) {
    http_response_code(404);
    echo view(VIEWS_PATH . '/shared/error.php', [
        'status_code' => 404,
        'message' => 'По Вашему запросу ничего не найдено'
    ]);
    return;
}

$active_project_id = $_GET['project_id'] ?? null;

// показывать или нет выполненные задачи
$show_complete_tasks = isset($_GET['show_completed'])
    ? intval($_GET['show_completed'])
    : 0;

$projects = getAllProjects($user['id']);
if ($projects === false) {
    http_response_code(500);
    echo view(VIEWS_PATH . '/shared/error.php', [
        'status_code' => 500,
        'message' => db_error()
    ]);
    return;
};

$tasks;

// ищем задачи одного проекта
if ($active_project_id) {
    $current_project = getProjectById($user['id'], $active_project_id);

    if ($current_project === false) {
        http_response_code(500);
        echo view(VIEWS_PATH . '/shared/error.php', [
            'status_code' => 500,
            'message' => db_error()
        ]);
        return;
    };

    if (count($current_project) === 0) {
        http_response_code(404);
        echo view(VIEWS_PATH . '/shared/error.php', [
            'status_code' => 404,
            'message' => 'Проект не найден'
        ]);
        return;
    };

    $tasks = getTasksByProject($user['id'], $active_project_id);
} else {
    // ищем задачи всех проектов
    $tasks = getAllTasks($user['id']);
};

if ($tasks === false) {
    http_response_code(500);
    echo view(VIEWS_PATH . '/shared/error.php', [
        'status_code' => 500,
        'message' => db_error()
    ]);
    return;
}

$visible_tasks = count($tasks) === 0
    ? $tasks
    : tasks_filter(
        fill_done_task(fill_important_task($tasks)),
        $show_complete_tasks
    );

$projects = fill_projects_data(
    $projects,
    $active_project_id,
    $_GET,
    pathinfo(__FILE__, PATHINFO_BASENAME)
);

$index_content = view(VIEWS_PATH . 'index.php', [
    'show_complete_tasks' => $show_complete_tasks,
    'visible_tasks' => $visible_tasks
]);

$project_nav = view(VIEWS_PATH . '/partials/projects_nav.php', [
    'projects' => $projects
]);

$content = view(VIEWS_PATH . '/shared/content_with_sidebar.php', [
    'content' => [
        'side' => $project_nav,
        'main' => $index_content
    ]
]);

$full_page = buildLayout([
    'has_sidebar' => (bool)$project_nav,
    'user' => $user,
    'content' => $content
]);

echo $full_page;


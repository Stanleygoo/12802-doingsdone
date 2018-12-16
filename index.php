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
$activeFilter = $_GET['filter'] ?? null;

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

if (isset($_GET['query'])) {
    $search_query = trim($_GET['query']);
    $search_query = strlen($search_query) > 0 ? $search_query : null;
} else {
    $search_query = null;
}

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

    $tasks = getTasks(
        $user['id'],
        $active_project_id,
        $activeFilter,
        $search_query
    );
} else {
    // ищем задачи всех проектов
    $tasks = getTasks(
        $user['id'],
        null,
        $activeFilter,
        $search_query
    );
};

if ($tasks === false) {
    http_response_code(500);
    echo view(VIEWS_PATH . '/shared/error.php', [
        'status_code' => 500,
        'message' => db_error()
    ]);
    return;
}

if (isset($_GET['task_id']) && isset($_GET['check'])) {
    $task_id = $_GET['task_id'] ?? null;
    $check_task = $_GET['check'] ?? null;

    $is_task_exist = count(array_filter($tasks, function($task) use($task_id) {
        return (int)$task['id'] === (int)$task_id;
    })) > 0;

    if ($is_task_exist) {
        $toggle_result = toggleTask($task_id, $check_task);

        if ($toggle_result === false) {
            http_response_code(500);
            echo view(VIEWS_PATH . '/shared/error.php', [
                'status_code' => 500,
                'message' => db_error()
            ]);
        } else {
            $script_name = pathinfo(__FILE__, PATHINFO_BASENAME);
            $query_params = $_GET;
            unset($query_params['task_id']);
            unset($query_params['check']);
            $redirect_url = '/' . $script_name . '?' . http_build_query($query_params);
            header("Location: $redirect_url");
        }
    }
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
    'index.php'
);

$taskFilters = buildTaskFilter(
    $activeFilter,
    $active_project_id,
    $show_complete_tasks,
    $search_query
);

$index_content = view(VIEWS_PATH . 'index.php', [
    'show_complete_tasks' => $show_complete_tasks,
    'visible_tasks' => $visible_tasks,
    'filters' => $taskFilters,
    'search_query' => $search_query,
    'has_no_results' => strlen($search_query) > 0 && count($visible_tasks) === 0
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


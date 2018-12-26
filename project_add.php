<?php

require_once('./bootstrap.php');

check_auth();

$active_project_id = $_GET['project_id'] ?? null;

$projects = get_all_projects($user['id']);

if ($projects === false) {
    http_response_code(500);
    echo view(VIEWS_PATH . '/shared/error.php', [
        'status_code' => 500,
        'message' => db_error()
    ]);
    return;
}

$projects = fill_projects_data(
    $projects,
    $active_project_id,
    $_GET,
    'index.php'
);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $form = $_POST;
    $errors = [];

    $schema = [
        'name' => [
            'required' => true
        ]
    ];

    list(
        'result' => $validate_result,
        'errors' => $errors
    ) = validate($form, $schema);

    if ($validate_result) {
        $is_already_exist = count(array_filter($projects, function($project) use($form) {
            return mb_strtolower($project['name']) === mb_strtolower($form['name']);
        })) > 0;

        if ($is_already_exist) {
            $errors['name'] = 'Проект с таким названием уже существует';
        }
    }

    if (count($errors) === 0) {
        $add_project_id = add_project($form['name'], $user['id']);
        if ($add_project_id === false) {
            http_response_code(500);
            echo view(VIEWS_PATH . '/shared/error.php', [
                'status_code' => 500,
                'message' => db_error()
            ]);
            return;
        } else {
            $redirect_url = '/?' . http_build_query([
                'project_id' => $add_project_id
            ]);
            header("Location: $redirect_url");
        }
    }
}

$add_content = view(VIEWS_PATH . 'project_add.php', [
    'form' => $form ?? [],
    'errors' => $errors ?? []
]);

$project_nav = view(VIEWS_PATH . '/partials/projects_nav.php', [
    'projects' => $projects
]);

$content = view(VIEWS_PATH . '/shared/content_with_sidebar.php', [
    'content' => [
        'side' => $project_nav,
        'main' => $add_content
    ]
]);

$full_page = build_layout([
    'has_sidebar' => (bool)$project_nav,
    'user' => $user,
    'content' => $content
]);

echo $full_page;

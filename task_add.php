<?php

require_once('./bootstrap.php');

check_auth();

$active_project_id = $_GET['project_id'] ?? null;

$projects = getAllProjects($user['id']);

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
    $task_form_data = $_POST;

    $schema = [
        'name' => [
            'required' => true,
            'validator' => function($data) {
                return !empty($data);
            },
            'message' => 'Имя задачи необходимо заполнить'
        ],
        'project' => [
            'required' => true,
            'validator' => function($data) use($projects) {
                $in_array = false;
                $project_id = $data;
                foreach($projects as $key => $project) {
                    if ($project['id'] === (int)$project_id) {
                        $in_array = true;
                        break;
                    }
                }
                return $in_array;
            },
            'message' => 'Выберите существующий проект'
        ],
        'date' => [
            'validator' => function($data) {
                list($y, $m, $d) = explode('-', $data);
                return checkdate($m, $d, $y);
            },
            'message' => 'Cрок выполнения должен быть датой в формате «ДД.ММ.ГГГГ»'
        ]
    ];

    $validate_result = validate($task_form_data, $schema);

    if (isset($_FILES['preview']['name'])) {
        $file_name = $_FILES['preview']['name'];
        $file_path = UPLOAD_DIR;
        $new_file_name = time().uniqid(rand()) . '-' . $file_name;
        if (move_uploaded_file($_FILES['preview']['tmp_name'], $file_path . $new_file_name)) {
            $task_form_data['file'] = $new_file_name;
        }
    }

    if ($validate_result['result']) {
        $task_form_data['author_id'] = $user['id'];

        $add_result = addTask($task_form_data);
        if ($add_result !== false) {
            $redirect_url = '/index.php?' . http_build_query([
                'project_id' => $task_form_data['project']
            ]);
            header("Location: $redirect_url");
        } else {
            echo view(VIEWS_PATH . '/shared/error.php', [
                'status_code' => 500,
                'message' => $add_result . db_error()
            ]);
        }
        return;
    }
}

$add_content = view(VIEWS_PATH . 'task_add.php', [
    'projects' => $projects,
    'task' => $task_form_data ?? [],
    'errors' => $validate_result['errors'] ?? []
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

$full_page = buildLayout([
    'has_sidebar' => (bool)$project_nav,
    'user' => $user,
    'content' => $content
]);

echo $full_page;


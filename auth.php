<?php

include_once('./bootstrap.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $form = $_POST;
    $errors = [];

    $schema = [
        'email' => [
            'required' => true,
            'validator' => function($data) {
                return filter_var($data, FILTER_VALIDATE_EMAIL) !== false;
            },
            'message' => 'E-mail введён некорректно'
        ],
        'password' => [
            'required' => true
        ],
    ];

    list(
        'result' => $validate_result,
        'errors' => $errors
    ) = validate($form, $schema);

    if ($validate_result) {
        $user_email = $form['email'];
        $existing_user = get_user_by_email($user_email);
        if ($existing_user === false) {
            echo view(VIEWS_PATH . '/shared/error.php', [
                'status_code' => 500,
                'message' => db_error()
            ]);
            return;
        } else {
            if (
                (count($existing_user) === 0) ||
                !password_verify($form['password'], $existing_user[0]['password'])
            ) {
                $errors['verify'] = 'Пользователя с такими данными не существует';
            }
            else {
                $_SESSION['user'] = $existing_user[0];
                header('Location: /index.php');
                exit();
            }
        }
    }
}

$auth_form = view(VIEWS_PATH . 'auth.php', [
    'errors' => $errors ?? [],
    'form' => $form ?? []
]);

$auth_side = view(VIEWS_PATH . '/partials/auth_side.php', []);

$content = view(VIEWS_PATH . '/shared/content_with_sidebar.php', [
    'content' => [
        'side' => $auth_side,
        'main' => $auth_form
    ]
]);

$full_page = build_layout([
    'has_sidebar' => (bool)$auth_side,
    'user' => $user,
    'content' => $content
]);

echo $full_page;


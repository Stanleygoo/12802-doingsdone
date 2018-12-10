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
        'name' => [
            'required' => true
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
        $existing_user = getUserByEmail($user_email);
        if ($existing_user === false) {
            echo view(VIEWS_PATH . '/shared/error.php', [
                'status_code' => 500,
                'message' => db_error()
            ]);
            return;
        } elseif (count($existing_user) > 0) {
            $errors['email'] = 'Пользователь с таким email уже существует';
        }
    }

    if (count($errors) === 0) {
        $user_for_register = [
            'name' => $form['name'],
            'email' => $form['email'],
            'password' => password_hash($form['password'], PASSWORD_DEFAULT)
        ];

        $register_result = addUser($user_for_register);

        if ($register_result !== false) {
            header("Location: /index.php");
        } else {
            echo view(VIEWS_PATH . '/shared/error.php', [
                'status_code' => 500,
                'message' => db_error()
            ]);
            return;
        }
    }
}

$register_form = view(VIEWS_PATH . 'register.php', [
    'errors' => $errors ?? [],
    'form' => $form ?? []
]);

$auth_side = view(VIEWS_PATH . '/partials/auth_side.php', []);

$content = view(VIEWS_PATH . '/shared/content_with_sidebar.php', [
    'content' => [
        'side' => $auth_side,
        'main' => $register_form
    ]
]);

$full_page = view(VIEWS_PATH . 'shared/layout.php', [
    'title' => 'Дела в порядке',
    'is_guest' => !$user,
    'has_sidebar' => (bool)$auth_side,
    'user' => $user,
    'content' => $content
]);

echo $full_page;

<?php

require_once 'data.php';
require_once 'functions.php';

define('VIEWS_PATH', __DIR__ . '/templates/');

// показывать или нет выполненные задачи
$show_complete_tasks = rand(0, 1);

// задачи, отфильтрованные для показа
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

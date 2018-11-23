<?php

// данные для проектов
$projects = [
    1 => 'Входящие',
    2 => 'Учеба',
    3 => 'Работа',
    4 => 'Домашние дела',
    5 => 'Авто'
];

// данные для задач
$tasks = [
    [
        'text' => 'Собеседование в IT компании',
        'deadline' => '01.12.2018',
        'project' => 3,
        'is_done' => false
    ],
    [
        'text' => 'Выполнить тестовое задание',
        'deadline' => '25.12.2018',
        'project' => 3,
        'is_done' => false
    ],
    [
        'text' => 'Сделать задание первого раздела',
        'deadline' => '21.12.2018',
        'project' => 2,
        'is_done' => true
    ],
    [
        'text' => 'Встреча с другом',
        'deadline' => '22.12.2018',
        'project' => 1,
        'is_done' => false
    ],
    [
        'text' => 'Купить корм для кота',
        'deadline' => 'Нет',
        'project' => 4,
        'is_done' => false
    ],
    [
        'text' => 'Заказать пиццу',
        'deadline' => 'Нет',
        'project' => 4,
        'is_done' => false
    ],
    [
        'text' => '
            <b>Протестировать защиту от <i>xss</i>-атак</b>
            <ul>
                <li><script>alert("alert")</script></li>
                <li>2</li>
                <li>3</li>
            </ul>
        ',
        'deadline' => 'Нет',
        'project' => 2,
        'is_done' => true
    ]
];

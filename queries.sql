-- Добавление пользователей
INSERT INTO `users` (`name`, `email`, `password`)
VALUES
  ('Иван Петров', 'ivan.petrov@company.com', '0123456789'),
  ('Александр Константинопольский', 'petr.ivanov@company.com', 'password');

-- Добавление проектов
INSERT INTO `projects` (`name`, `author_id`)
VALUES
  ('Входящие', 2),
  ('Учеба', 1),
  ('Работа', 2),
  ('Домашние дела', 1),
  ('Авто', 2);

-- Добавление задач
INSERT INTO `tasks` (
  `name`,
  `deadline`,
  `completed_date`,
  `status`,
  `file`,
  `project_id`,
  `author_id`
)
VALUES
  (
    'Собеседование в IT компании',
    STR_TO_DATE('01.12.2018', '%d.%m.%Y'),
    NULL,
    '0',
    'resume.pdf',
    3,
    (SELECT `author_id` FROM `projects` WHERE `id` = 3)
  ),
  (
    'Выполнить тестовое задание',
    STR_TO_DATE('25.12.2018', '%d.%m.%Y'),
    NULL,
    '0',
    'test-quest-code.zip',
    3,
    (SELECT `author_id` FROM `projects` WHERE `id` = 3)
  ),
  (
    'Сделать задание первого раздела',
    STR_TO_DATE('21.12.2018', '%d.%m.%Y'),
    NULL,
    '1',
    NULL,
    2,
    (SELECT `author_id` FROM `projects` WHERE `id` = 2)
  ),
  (
    'Встреча с другом',
    STR_TO_DATE('22.12.2018', '%d.%m.%Y'),
    NULL,
    '0',
    NULL,
    1,
    (SELECT `author_id` FROM `projects` WHERE `id` = 1)
  ),
  (
    'Купить корм для кота',
    NULL,
    NULL,
    '0',
    NULL,
    4,
    (SELECT `author_id` FROM `projects` WHERE `id` = 4)
  ),
  (
    'Заказать пиццу',
    NULL,
    NULL,
    '0',
    NULL,
    4,
    (SELECT `author_id` FROM `projects` WHERE `id`=4)
  ),
  (
    '
      <b>Протестировать защиту от <i>xss</i>-атак</b>
      <ul>
        <li><script>alert("alert")</script></li>
        <li>2</li>
        <li>3</li>
      </ul>
    ',
    STR_TO_DATE('27.11.2018', '%d.%m.%Y'),
    NULL,
    '1',
    NULL,
    2,
    (SELECT `author_id` FROM `projects` WHERE `id` = 2)
  )

-- получить список из всех проектов для одного пользователя
SELECT *
FROM `projects`
WHERE `author_id` = 2

-- получить список из всех задач для одного проекта
SELECT *
FROM `tasks`
WHERE `project_id` = 2

-- пометить задачу как выполненную
UPDATE `tasks`
SET `status` = '1'
WHERE `id` = 5

-- получить все задачи для завтрашнего дня
SELECT *
FROM `tasks`
WHERE `deadline` BETWEEN CURDATE() AND CURDATE() + INTERVAL 1 DAY

-- обновить название задачи по её идентификатору.
UPDATE `tasks`
SET `name` = '<i>Тест XSS</i>'
WHERE `id` = 7
